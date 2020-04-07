# Composer Substitution Plugin

Composer plugin replacing placeholders in the scripts section by dynamic values

[![Build Status](https://secure.travis-ci.org/villfa/composer-substitution-plugin.png?branch=master)](http://travis-ci.org/villfa/composer-substitution-plugin)
[![Latest Stable Version](https://poser.pugx.org/villfa/composer-substitution-plugin/v/stable)](https://packagist.org/packages/villfa/composer-substitution-plugin)
[![License](https://poser.pugx.org/villfa/composer-substitution-plugin/license)](./LICENSE)

## Installation

```sh
composer require villfa/composer-substitution-plugin
```

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
                "value": "John Doe"
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

### Substitution types

For each type of substitution the value replacing the placeholder comes from a different source.

* `literal`: The value in configuration is used directly.
* `callback`: The value is the string returned by a callback.
* `include`: The value is the string returned by a PHP file.
* `env`: The value is an ENV variable.
