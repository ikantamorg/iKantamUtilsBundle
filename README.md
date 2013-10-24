# iKantam Utils Bundle for symfony 2

### Basic Docs

* [Installation](#installation)

### Utls

* [String Utils](#stringutils)

<a name="installation"></a>
## Installation

Add on composer.json (see http://getcomposer.org/)

"require" :  {
// ...
"ikantam/utils-bundle":"dev-develop",
}

To start using the bundle, register it in your Kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    );
    // ...
}
```

<a name="stringutils"></a>
## String Utils
<br>
Add namespace

````
use iKantam\Bundles\UtilsBundle\Utils\StringUtils;
````

#### Randpm password generator:
<br>
to generate a random password, use:
````
$password = StringUtils::generatePassword(); //default password length 12 symbols
//or
$password = StringUtils::generatePassword(20); // generate password with length 20
````

<a name="twigutils"></a>
## Twig Utils
#### Absolute Url:
<br>
if you need to use absolute url in twig template - just add "absolute url" filter
````
{{ 'foo/bar'|absolute_url }} // --> http://absoluteurlpath/foo/bar
````
#### JSON decode:
<br>
if you need to decode json in twig template - just add "json_decode" filter to json
````
{% set Image = child.vars.label | json_decode %} // child.vars.label contains json data
````