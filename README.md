# Translation Bridge Bundle

This bundle aims to provide an easy way to generate links to same-pages-but-different-locales of a website.

## Example

Imagine you need to generate the list of localized URLs corresponding to the english one below:
```
http://mydomain.tld/en/category/drawing/pencil/ff0000
```

constructed via the following route (using BeSimpleI18nRoutingBundle)

```yml
front_category_item_color:
    locales:
        en: "en/category/{category_name}/{item_name}/{color_code}"
        fr: "fr/categorie/{category_name}/{item_name}/{color_code}"
        it: "it/categoria/{category_name}/{item_name}/{color_code}"
    defaults: { _controller: MyCategoryBundle:Category:itemColor }
```

Where `category_name` and `item_name` are localized but `color_code` is language independant.

Just add the following Annotation to your controller

```php
use Woecifaun\Bundle\TranslationBridgeBundle\Configuration\TranslationBridge;

…

/**
 * @ParamConverter(…)
 * @Template()
 * @TranslationBridge(
 *      "category_name" = "category.name",
 *      "item_name"     = "item.name",
 *      "color_code"    = "item.color"
 * )
 */
public function itemColorAction(Category $category, Item $item)
{
    …
}
```

The following array will then be injected in the template with your other variables:
```php
[
    'en' => 'http://mydomain.tld/en/category/drawing/pencil/ff0000',
    'fr' => 'http://mydomain.tld/fr/categorie/dessin/crayon/ff0000',
    'it' => 'http://mydomain.tld/en/categoria/disegno/matita/ff0000',
]
```

To generate the alternate links (see [Google article on translations](https://support.google.com/webmasters/answer/189077?hl=en)), just include the following code in the `<head>` element of your twig template:

```twig
{% include 'WoecifaunTranslationBridgeBundle::alternates.html.twig' %}
```

HTML code generated will be as following:
```html
…
<link rel="alternate" href="http://mydomain.tld/en/category/drawing/pencil/ff0000" hreflang="en" />
<link rel="alternate" href="http://mydomain.tld/fr/categorie/dessin/crayon/ff0000" hreflang="fr" />
<link rel="alternate" href="http://mydomain.tld/en/categoria/disegno/matita/ff000" hreflang="it" />
…
```

## Requirements

* __Use of ParamConverter:__
As the Translation Bridge Bundle tries to match annotation settings to controller arguments in order to call the correct methods on them, wildcards values need to be converted to PHP objects.
* __Use of @Template() Annotation:__
The Translation Bridge array will be injected in the array returned by the controller when used in conjonction with the Template() Annotation. If the value returned by the controller is not an array, the Translation Bridge logic won't even be run.
* __woecifaun.translationBridge.appLocales__ listing every locale affected by the Translation Bridge must be defined in your parameters file.

## Logic customization

In case you want or you need to change the logic handling the routes translation, just add the following line in your parameters file (with a real path):
```
woecifaun_translation_bridge.class: My\Bundle\MyBundle\PathTo\MyOwn\TranslationBridge
```

* The class must implement the `Woecifaun\Bundle\TranslationBridgeBundle\Model\TranslationBridgeInterface`.
* `router` and `property_accessor` services as well as the `appLocales` parameter (see above) will be automatically injected via the constructor.


## Installation

### Step 1: Enable the repository in your composer.json


Add the following lines to your `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/woecifaun/translation-bridge-bundle"
    }
],
```

> :package: Packagist install is coming, thus this step won't be needed anymore.

### Step 2: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```bash
$ composer require woecifaun/translation-bridge-bundle:~0.0
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

### Step 3: Enable the Bundle

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Woecifaun\Bundle\TranslationBridgeBundle\WoecifaunTranslationBridgeBundle(),
        );

        // ...
    }

    // ...
}
```
