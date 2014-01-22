Simplifies authentication for a Symfony server providing a JSON web API.

Provides the following on top of Fp/OpenIdBundle:

* OpenID logins
* Abstract User class that implements most of the annoying common stuff
* Security Controller action for logging out
* Session based on Authorization headers instead of cookies
* JSON responses to login/logout requests
* Server side of the reload-less OpenID login mechanism from http://openid-demo.appspot.com/

## Installation

- Install `ac/login-convenience-bundle` with composer
- Add and run a migration to create User and OpenIdIdentity tables
- Add ACLoginConvenienceBundle and FpOpenIdBundle to AppKernel
- Delete everything from security.yml but this:

```
ac_login_convenience:
    secured_paths:
        - /important-stuff
```

- Optionally, if you want to use the Authentication header to transmit the session key
rather than a cookie, add this setting to config.yml:

```
framework:
  session:
    storage_id: ac_login_convenience.session.storage.auth_header
```

## Usage

Add users to your system with the user:create command. You can specify an
OpenID identity path when doing so, which allows them to log in via that
identity.

If you don't want to specify the identity in advance, add entries to the
ac_login_convenience.trusted_providers config
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
