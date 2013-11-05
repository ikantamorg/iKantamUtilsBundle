# iKantam Utils Bundle (for symfony 2)

### Basic Docs

* [Installation](#installation)

### Utils

* [String Utils](#stringutils)
* [Twig Utils](#twigutils)

<a name="installation"></a>
## Installation

Add on composer.json (see http://getcomposer.org/)

```
"require" :  {
// ...
"ikantam/utils-bundle":"dev-develop",
}
```
To start using the bundle, register it in your Kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new iKantam\UtilsBundle\UtilsBundle(),
    );
    // ...
}
```

<a name="stringutils"></a>
## String Utils

Add namespace

````
use iKantam\UtilsBundle\Utils\StringUtils;
````

#### Random password generator:

to generate a random password, use:
````
$password = StringUtils::generatePassword(); //default password length 12 symbols
//or
$password = StringUtils::generatePassword(20); // generate password with length 20
````
##### Args:

* ``$length`` Length of generated password (Default: 12)

#### Token replacement:

If you want to replace some tokens in text use this:
````
$text = "You should eat %pizza%, %beer%, and %pizza% every day";

$tokens = array(
'pizza' => 'fruits',
'beer' => 'vegetables'
);

$replaced_text = StringUtils::tokensReplace($text, $tokens);
// You should eat fruits, vegetables, and fruits every day
````
##### Args:

* ``$text`` The text you want to change
* ``$replacement`` Array of tokens and their replacements
* ``$token_symbol`` Symbol that identifies the token from the text (Default: %)
* ``$wrap_side`` Side to which is added the ``$token_symbol``. Allow values: ``left, right, both`` (Default: both)

<a name="twigutils"></a>
## Twig Utils
#### Absolute Url:

if you need to use absolute url in twig template - just add "absolute url" filter

````
{{ 'foo/bar'|absolute_url }} // --> http://absoluteurlpath/foo/bar
````
#### JSON decode:

if you need to decode json in twig template - just add "json_decode" filter to json

````
{% set Image = child.vars.label | json_decode %} // child.vars.label contains json data
````

#### FOS JsRoutingBundle Options:

Do you use FOSJsRoutingBundle? Do you try to configure it for some different environments?

``{{ fos_js_routes_options() }}``  -  will save you from these problems.

It will setup: ``base_url``, ``scheme``, ``host``, ``prefix``.

Add code below to your base layout:

````
<script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
<script src="{{ asset('path_to/routes.js') }}"></script>
// ...
<script>
    {{ fos_js_routes_options() }}
</script>
````