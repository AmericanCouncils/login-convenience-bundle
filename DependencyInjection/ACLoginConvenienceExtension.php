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
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'ac_login_convenience.trusted_openid_providers',
            $config['trusted_openid_providers']
        );

        $container->setParameter(
            'ac_login_convenience.openid_path',
            $config['openid_path']
        );

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

        $container->prependExtensionConfig("security",
            $this->generateSecurityConf($config)
        );

        # TODO: Use Mongo to store identities if app is storing users there
        $container->prependExtensionConfig("fp_open_id", [
            "db_driver" => "orm",
            "identity_class" => "AC\LoginConvenienceBundle\Entity\OpenIdIdentity"
        ]);
    }

    private function generateSecurityConf($config)
    {
        $oidPath = $config['openid_path'];

        $securityConf = [
            "providers" => [
                "app_users" => [
                    # TODO: Allow app to use Mongo instead of Doctrine ORM
                    "entity" => [
                        "class" => $config['user_entity_class'],
                        "property" => "email"
                    ]
                ],
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

        foreach ($config['secured_paths'] as $path) {
            $securityConf["access_control"][] = [
                "path" => "^$path/.+",
                "roles" => "IS_AUTHENTICATED_FULLY"
            ];
        }

        return $securityConf;
    }
}
