# Composer Substitution Plugin

The [Composer](https://getcomposer.org/) Substitution plugin replaces placeholders in the scripts section by dynamic values.

It also permits to cache these values during the command execution and adds the ability to escape them with the function of your choice.

[![Travis Build Status](https://secure.travis-ci.org/villfa/composer-substitution-plugin.png?branch=master)](http://travis-ci.org/villfa/composer-substitution-plugin)
[![AppVeyor Build Status](https://ci.appveyor.com/api/projects/status/github/villfa/composer-substitution-plugin?branch=master&svg=true)](https://ci.appveyor.com/project/villfa/composer-substitution-plugin)
[![Latest Stable Version](https://poser.pugx.org/villfa/composer-substitution-plugin/v/stable)](https://packagist.org/packages/villfa/composer-substitution-plugin)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3.2-8892BF.svg?style=flat-square)](https://php.net/)
[![License](https://poser.pugx.org/villfa/composer-substitution-plugin/license)](./LICENSE)

## Installation

```sh
composer require villfa/composer-substitution-plugin
```

## Requirements

* PHP >= 5.3.2
* Composer >= 1.0.0

## Usage

You need to configure the plugin in the *extra* section of `composer.json`.

Here an example:

```json
"extra": {
    "substitution": {
        "enable": true,
        "mapping": {
            "{MY_NAME}": {
                "type": "literal",
                "value": "John Doe",
                "escape": "addslashes"
            },
            "{PHP_VERSION}": {
                "type": "callback",
                "value": "phpversion"
            },
            "{DB_STATUS}": {
                "type": "include",
                "value": "./scripts/db.php",
                "cached": true
            },
            "{HOME}": {
                "type": "env",
                "value": "HOME"
            },
            "{COMPOSER_VERSION}": {
                "type": "constant",
                "value": "Composer\\Composer::VERSION"
            },
            "{NPROC}": {
                "type": "process",
                "value": "nproc"
            }
        }
    }
}
```

Then you can add the configured placeholders in the *scripts* section:

```json
"scripts": {
    "welcome": "echo 'Hi {MY_NAME}, the database is {DB_STATUS}.'"
}
```

And now if you run the command:

```sh
$ composer run-script welcome
Hi John Doe, the database is OK.
```

### Configuration

Configuration key | Mandatory | Type | Default value | Description
----------------- | --------- | ---- | ------------- | -----------
extra.substitution.enable | yes | bool | false | Enables the plugin when true
extra.substitution.mapping | yes | object | empty object | Mapping between placeholders (the keys) and substitution rules (the values). There is no restriction with the placeholders format.
extra.substitution.mapping.*.type | yes | string | n/a | Substitution type (see the related section below)
extra.substitution.mapping.*.value | yes | string | n/a | Substitution value (depends on the type)
extra.substitution.mapping.*.cached | false | bool | false | Indicates whether the value provided after the first substitution must be cached
extra.substitution.mapping.*.escape | false | string | null | Escaping function that will receive the substitute value as argument
extra.substitution.priority | false | integer | 0 | Plugin's event handler priority (see [Composer documentation](https://getcomposer.org/doc/articles/plugins.md#event-handler))

### Substitution types

For each type of substitution the value replacing the placeholder comes from a different source.

* `literal`: The value in configuration is used directly.
* `callback`: The value is the string returned by a callback.
* `include`: The value is the string returned by a PHP file.
* `env`: The value is an ENV variable.
* `constant`: The value comes from a constant or a class constant.
* `process`: The value is the output of the processed command.

## Real-life examples

### [PHPUnit Extra Constraints](https://github.com/villfa/phpunit-extra-constraints)

This library defines a Composer script which uses [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) this way:
```sh
"scripts": {
    "phpcs": "phpcs --standard=PSR12 --parallel=$(nproc) src/ tests/",
```
Unfortunately because of the usage of `nproc` it is not cross-platform.

This is solved by the substitution plugin in combination with [Linfo](https://github.com/jrgp/linfo)
(See also the tiny script [nproc.php](https://github.com/villfa/phpunit-extra-constraints/blob/a2c8e5a6f5079f4a2c9d83f45283ad25330ae16b/scripts/nproc.php)).
Here how it is configured:

```json
"extra": {
    "substitution": {
        "enable": true,
        "mapping": {
            "$(nproc)": {
                "type": "include",
                "value": "./scripts/nproc.php",
                "cached": true
            }
        }
    }
}
```

So now it also works on Windows without even touching the *scripts* section.
