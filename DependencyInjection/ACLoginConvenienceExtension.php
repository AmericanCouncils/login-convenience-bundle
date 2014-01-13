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

        $loader = new Loader\YamlFileLoader(
            $container, new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = $this->processConfiguration(
            new Configuration(),
            $container->getExtensionConfig($this->getAlias())
        );

        $securityConf = [
            "providers" => [
                "app_users" => [
                    # TODO: Allow app to use Mongo instead of Doctrine ORM
                    "entity" => [
                        "class" => $config['user_class'],
                        "property" => "email"
                    ]
                ]
            ],
            "firewalls" => [
                "main" => [
                    "pattern" => "^/",
                    "anonymous" => true,
                    "logout" => [ "path" => "/openid/logout" ]
                ]
            ],
            "access_control" => []
        ];

        foreach ($config['secured_paths'] as $path) {
            $securityConf["access_control"][] = [
                "path" => "^$path/.+",
                "roles" => "IS_AUTHENTICATED_FULLY"
            ];
        }

        if (isset($config['openid_path']) && !is_null($config['openid_path'])) {
            $oid_path = $config['openid_path'];
            $securityConf["providers"]["openid_user_manager"] = [
                "id" => "ac_login_convenience.openid_user_manager"
            ];
            $securityConf["firewalls"]["main"]["fp_openid"] = [
                "create_user_if_not_exists" => true,
                "login_path" => "$oid_path/login_openid",
                "check_path" => "$oid_path/login_check_openid",
                "provider" => "openid_user_manager",
                "required_attributes" => [ "contact/email" ],
                "success_handler" => "ac_login_convenience.success_handler",
                "failure_handler" => "ac_login_convenience.failure_handler"
            ];
            array_unshift($securityConf["access_control"], [
                "path" => "^$oid_path/.+",
                "roles" => "IS_AUTHENTICATED_ANONYMOUSLY"
            ]);

            # TODO: Use Mongo to store identities if app is storing users there
            $container->prependExtensionConfig("fp_open_id", [
                "db_driver" => "orm",
                "identity_class" => "AC\LoginConvenienceBundle\Entity\OpenIdIdentity"
            ]);
        }

        $container->prependExtensionConfig("security", $securityConf);
    }
}
