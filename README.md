# Translation Bridge Bundle

This bundle aims to provide an easy way to generate links to same-pages-but-different-locales of a website.

Example
=======

You want to generate the list of localized URLs corresponding to the english page:
```
http://mydomain.tld/en/category/drawing/pencil/ff0000
```

Its constructed via the following route (using BeSimpleI18nRoutingBundle)

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

To generate the alternate links (see [Google article on translations](https://support.google.com/webmasters/answer/189077?hl=en)), just include the following code in the `<head>` element of your page:

```twig
{% include 'WoecifaunTranslationBridgeBundle::alternates.html.twig' %}
```


Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```bash
$ composer require woecifaun/translation-bridge-bundle:dev-master
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

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

            new Woecifaun\Bundle\TranslationBridge\WoecifaunTranslatioBridgeBundle(),
        );

        // ...
    }

    // ...
}
```
