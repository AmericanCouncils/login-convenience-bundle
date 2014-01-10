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
- Delete everything from security.yml but this:


```
ac_login_convenience:
    openid_path: /openid
```

- Add this to routing.yml:

```
openid_security:
    resource: "@FpOpenIdBundle/Resources/config/routing/security.xml"
    prefix: /openid
```
