Simplifies OpenID authorization for a Symfony server providing a JSON web API.

Provides the following on top of Fp/OpenIdBundle:

* Abstract User class that implements most of the annoying common stuff
* Security Controller action for logging out
* Session based on Authorization headers instead of cookies
* JSON responses to login/logout requests
* Generation of Access-Control-Allow-foo headers
* Server side of the reload-less OpenID login mechanism from http://openid-demo.appspot.com/
