Simplifies OpenID authorization for a Symfony server providing a JSON web API.

Provides the following on top of Fp/OpenIdBundle:

* Abstract User class that implements most of the annoying common stuff.
* Controller method for logging out.
* A session mechanism based on Authorization headers instead of cookies.
* JSON responses to login/logout requests.
* Generation of Access-Control-Allow-* headers
