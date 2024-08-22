# Silverstripe Consoler

Print debug statements from your backend to your browser console.

## Installation

```sh
composer require --dev silverstripe-consoler
```

NB: By default, it is configured to be enabled only in dev environment. Along with installing it as a required-dev package, this should avoid accidentally leaking data in a production environment.

## Documentation

The package is namespaced `clodeindustrie/silverstripe-consoler` however, the class is namespaced `clie/Consoler` that way it is bit easier to user directly in any class without having to `use ...` it at the top the file.

```php
// Print strings
\Clie\Consoler::log('This is a debugging statement');

// Print Arrays
\Clie\Consoler::log([ "my-key" => "my-value"]);

// Print DataObjects
\Clie\Consoler::log(Member::get()->first());
```


![screenshot](./doc/screenshot.png)
