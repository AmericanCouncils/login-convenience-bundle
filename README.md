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
