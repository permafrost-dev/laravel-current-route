### Laravel Current Route

---
Helper functions and class to retrieve information about the current route in Laravel.


![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/permafrost-dev/laravel-current-route?label=version&sort=semver&style=flat-square)

---

#### Installation

You can install the package via composer:

```bash
composer require permafrost-dev/laravel-current-route
```
---

#### Helper Functions

```php 

// Return a Laravel Route object for the current route.
function current_route();

// Return a class containing information about the current route.
function current_route_info();

// Return the action for the current route.
function current_route_action();

// Return true if one of the specified wildcard patterns matches the name of the current route.
function current_route_matches($patterns);

// Return the name for the current route.
function current_route_name();

// Returns true if $name matches the name of the current route.
function current_route_named($name);
```

#### Usage

_WIP_

#### Examples

Use `current_route_named()` to generate conditional css classes in a blade template:
```html
<a class="{{ current_route_named('dashboard') ? 'active' : 'inactive' }}"
    href="{{ route('dashboard') }}">Dashboard
</a>
```

---

#### Testing

``` bash
$ vendor/bin/phpunit
```

---

#### License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
