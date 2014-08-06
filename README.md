Simplifies authentication for a Symfony server providing a JSON web API.

Provides the following on top of Fp/OpenIdBundle:

* OpenID logins
* Abstract User class that implements most of the annoying common stuff
* Security Controller action for logging out
* Session based on Authorization headers instead of cookies
* JSON responses to login/logout requests
* Server side of the reload-less OpenID login mechanism from http://openid-demo.appspot.com/
* Dummy login mode for development and staging

## Installation

- Install `ac/login-convenience-bundle` with composer
- If using SQL: Add and run a migration to create User and OpenIdIdentity tables
- Add ACLoginConvenienceBundle and FpOpenIdBundle to AppKernel
- Delete everything from security.yml but this:

```
ac_login_convenience:
    secured_paths:
        - /important-stuff
```

- Add the following to your routing.yml:

```
ac_login_convenience:
    resource: "."
    type: "ac_login_convenience_routes"
```

- Optionally, if you want to use the Authentication header to receive the session key
rather than a cookie, set this in config.yml:

```
framework:
  session:
    storage_id: ac_login_convenience.session.storage.auth_header
```

## Usage

Add users to your system with the `create-user` command. You can specify an
OpenID identity path when doing so, which allows them to log in via that
identity.

## Adding OpenID identities

If you don't want to specify the identity in advance, add entries to the
`ac_login_convenience.trusted_providers` config
option for any OpenID providers that you trust to authenticate previously
unknown users:

```
ac_login_convenience:
    trusted_providers:
        - https://somebody.trustworthy.com/openid
```

Any user who logs in via a trusted provider can have that identity matched
via email address to existing users you've created. This does require that the provider supply the
user's email via the "contact/email" AX field, but this is pretty common.

Alternately, you can make your own OpenID registration system. After verifying
in some secure way that an identity URL really does belong to a user, you can call
the `associateIdentityWithUser` method on `ac_login_convenience.openid_user_manager`
to allow logins for that user with that identity.

## Other options

These can be specified under the ac_login_convenience config section:

* `dummy_mode`: If true, then instead of actually making OpenID checks
   on logins, the user can simply pick from a list of all the users
   in the database. This is useful for the dev environment, but
   obviously it should not be used on production systems.

* `db_driver`: Defaults to `orm`, meaning that users and identities are
  accessed via Doctrine ORM. Alternately, you can specify `mongodb`.

* `user_model_class`: You can use your own User class instead of the
  one that comes with LoginConvenienceBundle; specify the fully namespaced
  name of the class here. You should derive it from `Entity\AbstractEntityUser`
  or `Document\AbstractDocumentUser`, depending on which `db_driver` setting
  you are using.

* `api_keys`: An optional hash map from user email addresses to API keys.
  If supplied, then clients can use an "Authorization: Key foobar" header
  to directly access your app as the given user with key "foobar", without
  having to go through the full OpenID-authentication and session-creation process.
