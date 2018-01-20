# Version 3

Removed [Pimple](http://pimple.sensiolabs.org/) dependency.
You're supposed to inject dependencies manually (however you see fit).

To prevent conflicts with Symfony framework's `csrf_token()` Twig function,
all Twig functions provided by this component have been renamed.
Namely:
- `csrf_token()` -> `devture_csrf_token()`
- `render_form_violations()` -> `devture_form_render_violations()`
- `render_form_csrf_token()` -> `devture_form_render_csrf_token()`

Due to the code's reliance on type-hints, this version requires PHP >= 7.1.


# Version 2

Compatibility with [Pimple](http://pimple.sensiolabs.org/) v3 and [Twig](http://twig.sensiolabs.org/) v2.


# Version 1

Initial release targetting [Pimple](http://pimple.sensiolabs.org/) v1.
