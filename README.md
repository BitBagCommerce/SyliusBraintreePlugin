<h1 align="center">
    <a href="http://bitbag.shop" target="_blank">
        <img src="doc/logo.png" width="35%" />
    </a>
    <a href="https://packagist.org/packages/bitbag/braintree-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/bitbag/braintree-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/braintree-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/bitbag/braintree-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/BitBagCommerce/SyliusBraintreePlugin" title="Build status" target="_blank">
            <img src="https://img.shields.io/travis/BitBagCommerce/SyliusBraintreePlugin/master.svg" />
        </a>
    <a href="https://scrutinizer-ci.com/g/BitBagCommerce/SyliusBraintreePlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusBraintreePlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/braintree-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/bitbag/braintree-plugin/downloads" />
    </a>
</h1>

## Overview

This plugin allows you to integrate Braintree payment with Sylius platform app. It includes all Sylius and Braintree payment features, including refunding orders.

## Support

We work on amazing eCommerce projects on top of Sylius and Pimcore. Need some help or additional resources for a project?
Write us an email on mikolaj.krol@bitbag.pl or visit [our website](https://bitbag.shop/)! :rocket:

## Demo

We created a demo app with some useful use-cases of the plugin! Visit [demo.bitbag.shop](https://demo.bitbag.shop) to take a look at it. 
The admin can be accessed under [demo.bitbag.shop/admin](https://demo.bitbag.shop/admin) link and `sylius: sylius` credentials.

## Installation
```bash
$ composer require bitbag/braintree-plugin 
```
    
Add plugin dependencies to your AppKernel.php file:

```php
$bundles = [
   new \BitBag\SyliusBraintreePlugin\BitBagSyliusBraintreePlugin(),
];
```

Import configuration:

```yaml
imports:
    ...
    
    - { resource: "@BitBagSyliusBraintreePlugin/Resources/config/config.yml" }
```

## Customization

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

Run the below command to see what Symfony services are shared with this plugin:
 
```bash
$ bin/console debug:container bitbag_sylius_braintree_plugin
```

## Testing

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn build
$ bin/console assets:install public -e test
$ bin/console doctrine:database:create -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -e test
$ open http://localhost:8080
$ vendor/bin/behat
$ vendor/bin/phpspec run
$ vendor/bin/phpunit
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.
