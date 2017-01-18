#Laravel Helpers
[![Latest Stable Version](https://poser.pugx.org/curtissaunders/laravel-helpers/version)](https://packagist.org/packages/curtissaunders/laravel-helpers) 
[![Total Downloads](https://poser.pugx.org/curtissaunders/laravel-helpers/downloads)](https://packagist.org/packages/curtissaunders/laravel-helpers) 
[![Latest Unstable Version](https://poser.pugx.org/curtissaunders/laravel-helpers/v/unstable)](https://packagist.org/packages/curtissaunders/laravel-helpers) 
[![License](https://poser.pugx.org/curtissaunders/laravel-helpers/license.svg)](https://packagist.org/packages/curtissaunders/laravel-helpers)

#Installation

This package requires PHP 5.6+, and includes a Laravel 5 Service Provider.

To install through composer include the package in your `composer.json`.

    "curtissaunders/laravel-helpers": "1.0.*"

Run `composer install` or `composer update` to download the dependencies or you can run `composer require curtissaunders/laravel-helpers`.

##Laravel 5 Integration

To use the package with Laravel 5, add the Helper Service Provider to the list of service providers 
in `app/config/app.php`.

    'providers' => [

      CurtisSaunders\LaravelHelpers\HelpersServiceProvider::class
              
    ];
    
## Available helpers

* **versioned_asset** will apply a cache busting query string to your assets.
* **concat** will concatenate strings together
* **concat_ws** will concatenate strings together with the separator being defined as the first argument

*Example of versioned_asset:*

`{{ versioned_asset('images/photo.png') }}`

outputs:

`http://mysite.com/images/photo.png?v=392104829`

*Example of concat:*

`{{ concat('John', 'Terry', 'Dave') }}`

outputs:

`John Terry Dave`

*Example of concat_ws:*

`{{ concat(' - ', 'John', 'Terry', 'Dave') }}`

outputs:

`John - Terry - Dave`

