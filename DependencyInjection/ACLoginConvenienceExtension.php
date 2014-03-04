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
        $dbDriver = $container->getParameter('ac_login_convenience.db_driver');
        $oidPath = $container->getParameter('ac_login_convenience.openid_path');
        $securedPaths = $container->getParameter('ac_login_convenience.secured_paths');

        $secConf = $this->generateSecurityConf($oidPath, $securedPaths);
        $userProviderKey = $dbDriver;
        if ($dbDriver == "orm") { $userProviderKey = "entity"; }
        $secConf["providers"]["app_users"][$userProviderKey] = [
            "class" => $userClass,
            "property" => "email"
        ];
        $container->prependExtensionConfig("security", $secConf);

        $container->prependExtensionConfig("fp_open_id", [
            "db_driver" => $dbDriver,
            "identity_class" => "AC\LoginConvenienceBundle\Entity\OpenIdIdentity"
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
            'ac_login_convenience.openid_path',
            $config['openid_path']
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
            throw new \Exception("Unknown setting for db_driver");
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
            }
        }
        $container->setParameter(
            'ac_login_convenience.user_model_class',
            $userClass
        );

    }

    private function generateSecurityConf($oidPath, $securedPaths)
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
                        "login_path" => "$oidPath/login_openid",
                        "check_path" => "$oidPath/login_check_openid",
                        "provider" => "openid_user_manager",
                        "required_attributes" => [ "contact/email" ],
                        "success_handler" => "ac_login_convenience.success_handler",
                        "failure_handler" => "ac_login_convenience.failure_handler"
                    ]
                ]
            ],
            "access_control" => [
                [
                    "path" => "^$oidPath/.+",
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
