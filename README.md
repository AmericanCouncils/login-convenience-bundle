Simplifies authentication for a Symfony server providing a JSON web API.

Provides the following on top of Fp/OpenIdBundle:

* OpenID logins
* Abstract User class that implements most of the annoying common stuff
* Security Controller action for logging out
* Session based on Authorization headers instead of cookies
* JSON responses to login/logout requests
* Server side of the reload-less OpenID login mechanism from http://openid-demo.appspot.com/

## Usage

- Install with composer
- Add ACLoginConvenienceBundle and FpOpenIdBundle to AppKernel
- Add and run a migration to create User and OpenIdIdentity tables
- Setup app-level config files:

security.yml

```
security:
    providers:
        openid_user_manager:
            id: ac_login_convenience.openid_user_manager
        app_users:
            entity: {class: AC\LoginConvenienceBundle\Entity\User, property: email}

    firewalls:
        main:
            pattern: ^/
            anonymous: true
            logout:
                path: /openid/logout
            fp_openid:
                create_user_if_not_exists: true
                login_path: /openid/login_openid
                check_path: /openid/login_check_openid
                provider: openid_user_manager
                required_attributes: [ contact/email ]
                optional_attributes: [ namePerson/first, namePerson/last, person/guid ]
                success_handler: ac_login_convenience.success_handler
                failure_handler: ac_login_convenience.failure_handler

    access_control:
        - { path: ^/openid/.+, roles: [ IS_AUTHENTICATED_ANONYMOUSLY ] }
        - { path: ^/api/.+, roles: [ IS_AUTHENTICATED_FULLY ] }
```

config.yml

```
fp_open_id:
  db_driver: orm
  identity_class: AC\LoginConvenienceBundle\Entity\OpenIdIdentity

ac_login_convenience:
  user_provider: security.user.provider.concrete.app_users
```

routing.yml

```
openid_security:
    resource: "@FpOpenIdBundle/Resources/config/routing/security.xml"
    prefix: /openid
```
