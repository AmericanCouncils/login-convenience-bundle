<?php

namespace AC\LoginConvenienceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ACLoginConvenienceExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $serviceLoader = new Loader\YamlFileLoader(
            $container, new FileLocator(__DIR__.'/../Resources/config')
        );
        $serviceLoader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = $this->processConfiguration(
            new Configuration(),
            $container->getExtensionConfig($this->getAlias())
        );

        $this->setParameters($container, $config);

        $userClass = $container->getParameter('ac_login_convenience.user_model_class');
        $identityClass = $container->getParameter('ac_login_convenience.openid_identity_class');
        $dbDriver = $container->getParameter('ac_login_convenience.db_driver');
        $securedPaths = $container->getParameter('ac_login_convenience.secured_paths');

        $secConf = $this->generateSecurityConf($securedPaths);
        $userProviderKey = $dbDriver;
        if ($dbDriver == "orm") { $userProviderKey = "entity"; }
        $secConf["providers"]["app_users"][$userProviderKey] = [
            "class" => $userClass,
            "property" => "email"
        ];
        $container->prependExtensionConfig("security", $secConf);

        $container->prependExtensionConfig("fp_open_id", [
            "db_driver" => $dbDriver,
            "identity_class" => $identityClass
        ]);
    }

    private function setParameters($container, $config)
    {
        $container->setParameter(
            'ac_login_convenience.trusted_openid_providers',
            $config['trusted_openid_providers']
        );

        $container->setParameter(
            'ac_login_convenience.secured_paths',
            $config['secured_paths']
        );

        $container->setParameter(
            'ac_login_convenience.db_driver',
            $config['db_driver']
        );

        $persistenceService = null;
        if ($config["db_driver"] == "orm") {
            $persistenceService = "doctrine";
        } elseif ($config["db_driver"] == "mongodb") {
            $persistenceService = "doctrine_mongodb";
        } else {
            throw new \UnexpectedValueException("Unknown setting for db_driver");
        }
        $container->setParameter(
            'ac_login_convenience.db_persistence_service',
            $persistenceService
        );

        $userClass = $config["user_model_class"];
        if (is_null($userClass)) {
            if ($config["db_driver"] == "orm") {
                $userClass = "AC\LoginConvenienceBundle\Entity\User";
            } elseif ($config["db_driver"] == "mongodb") {
                $userClass = "AC\LoginConvenienceBundle\Document\User";
            } else {
                throw new \UnexpectedValueException(
                    "Cannot guess user_model_class from db_driver"
                );
            }
        }
        $container->setParameter(
            'ac_login_convenience.user_model_class',
            $userClass
        );

        $identityClass = null;
        if ($config["db_driver"] == "orm") {
            $identityClass = "AC\LoginConvenienceBundle\Entity\OpenIdIdentity";
        } elseif ($config["db_driver"] == "mongodb") {
            $identityClass = "AC\LoginConvenienceBundle\Document\OpenIdIdentity";
        } else {
            throw new \UnexpectedValueException(
                "Cannot guess openid_identity_class from db_driver"
            );
        }
        $container->setParameter(
            'ac_login_convenience.openid_identity_class',
            $identityClass
        );

    }

    private function generateSecurityConf($securedPaths)
    {
        $securityConf = [
            "providers" => [
                "app_users" => [],
                "openid_user_manager" => [
                    "id" => "ac_login_convenience.openid_user_manager"
                ]
            ],
            "firewalls" => [
                "main" => [
                    "pattern" => "^/",
                    "anonymous" => true,
                    "logout" => [ "path" => "/openid/logout" ],
                    "fp_openid" => [
                        "create_user_if_not_exists" => true,
                        "login_path" => "/openid/login_openid",
                        "check_path" => "/openid/login_check_openid",
                        "provider" => "openid_user_manager",
                        "required_attributes" => [ "contact/email" ],
                        "success_handler" => "ac_login_convenience.success_handler",
                        "failure_handler" => "ac_login_convenience.failure_handler"
                    ]
                ]
            ],
            "access_control" => [
                [
                    "path" => "^/openid/.+",
                    "roles" => "IS_AUTHENTICATED_ANONYMOUSLY"
                ]
            ]
        ];

        foreach ($securedPaths as $path) {
            $securityConf["access_control"][] = [
                "path" => "^$path/.+",
                "roles" => "IS_AUTHENTICATED_FULLY"
            ];
        }

        return $securityConf;
    }
}
