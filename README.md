Simplifies authentication for a Symfony server providing a JSON web API.

Provides the following on top of Fp/OpenIdBundle:

* OpenID logins
* Abstract User class that implements most of the annoying common stuff
* Security Controller action for logging out
* Session based on Authorization headers instead of cookies
* JSON responses to login/logout requests
* Server side of the reload-less OpenID login mechanism from http://openid-demo.appspot.com/
