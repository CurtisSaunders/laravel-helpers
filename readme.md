# Laravel Helpers
[![Latest Stable Version](https://poser.pugx.org/curtissaunders/laravel-helpers/version)](https://packagist.org/packages/curtissaunders/laravel-helpers) 
[![Total Downloads](https://poser.pugx.org/curtissaunders/laravel-helpers/downloads)](https://packagist.org/packages/curtissaunders/laravel-helpers) 
[![Latest Unstable Version](https://poser.pugx.org/curtissaunders/laravel-helpers/v/unstable)](https://packagist.org/packages/curtissaunders/laravel-helpers) 
[![License](https://poser.pugx.org/curtissaunders/laravel-helpers/license.svg)](https://packagist.org/packages/curtissaunders/laravel-helpers)

# Installation

This package requires PHP 5.6+, and includes a Laravel 5 Service Provider.

To install through composer include the package in your `composer.json`.

    "curtissaunders/laravel-helpers": "1.0.*"

Run `composer install` or `composer update` to download the dependencies or you can run `composer require curtissaunders/laravel-helpers`.

## Laravel 5 Integration

To use the package with Laravel 5, add the Helper Service Provider to the list of service providers 
in `app/config/app.php`.

    'providers' => [

      CurtisSaunders\LaravelHelpers\HelpersServiceProvider::class
              
    ];
    
## Available helpers

* [**versioned_asset**](#versionedAsset) will apply a cache busting query string to your assets.
* [**concat**](#concat) will concatenate strings together
* [**concat_ws**](#concat_ws) will concatenate strings together with the separator being defined as the first argument
* [**generate_uuid**](#generate_uuid) will generate a valid RFC 4122 UUID
* [**route_is/routeIs**](#route_is) will check if the current route matches the route passed
* [**query_log_to_sql**](#query_log_to_sql) will allow you to log a database query to a variable and dump it out for easy debugging
* [**combine_query**](#combine_query) will combine a query with its bindings

### <a id="versionedAsset"></a>***Example of versioned_asset:***

`{{ versioned_asset('images/photo.png') }}`

outputs:

`http://mysite.com/images/photo.png?v=392104829`

### <a id="concat"></a>***Example of concat:***

`{{ concat('John', 'Terry', 'Dave') }}`

outputs:

`John Terry Dave`

### <a id="concat_ws"></a>***Example of concat_ws:***

`{{ concat_ws(' - ', 'John', 'Terry', 'Dave') }}`

outputs:

`John - Terry - Dave`

## <a id="generate_uuid"></a>***Example of generate_uuid:***

`{{ generate_uuid() }}`

outputs:

`e4eaaaf2-d142-11e1-b3e4-080027620cdd`

When using the `generate_uuid` function, you are able to generate valid RFC 1, 3, 4 and 5 versions. In order to change
the version, simply pass the version number you require as the first argument (defaults to 1). For example, to generate
a version 4 Uuid, you can do the following:

`{{ generate_uuid(4) }}`

outputs:

`25769c6c-d34d-4bfe-ba98-e0ee856f3e7a`

For versions 3 and 5, you are also required to pass in a string as the second argument. This is hashed and used when
generating the Uuid. For example:

`{{ generate_uuid(3, 'php.net') }}`

outputs:

`11a38b9a-b3da-360f-9353-a5a725514269`

## <a id="route_is"></a>***Example of route_is() or routeIs():***

*Examples shown in Laravel Blade*

```
@if(route_is('about.index'))
// Do something
@else
// Do something else
@endif
```

Alternatively

```
@if(routeIs('about.index'))
// Do something
@else
// Do something else
@endif
```

You can also check for specific parameters by passing them in an array as the second argument. For example, you may want to check that you're on a specific product category to apply an "active" class to a link. Consider the below when looping through category links:
```
@foreach($categories as $category)
    <a href="{{ route('product.category', [$category->slug]) }}" class="{{ route_is('product.category', ['categorySlug' => $category->slug]) ? 'active' : '' }}">
        {{ $category->name }}    
    </a>
@endforeach
```
The above would apply a class of "active" when you're on the corresponding page to that link.

## <a id="query_log_to_sql"></a>***Example of query_log_to_sql:***

```
// Enable laravel's query log
DB::connection()->enableQueryLog();
 
... // Do database transactions ...
 
// Get all the queries ran since the query log was enabled
$queryLog = DB::getQueryLog();
 
// Combine the query logs ran with their bindings into the sql that was ran
$sqlQueries = query_log_to_sql($queryLog);
 
// Returns an array of all the sql queries ran with their bindings in place, useful for quick debugging
dd($sqlQueries);
```

## <a id="combine_query"></a>***Example of combine_query:***

```
// Create a query using Eloquent
$eloquentQuery = UserModel::where('email', '=', 'user.name@example.com');
 
// Combine the Eloquent query sql and bindings into a query you can run in mysql
$sqlQuery = combine_query($eloquentQuery->toSql(), $eloquentQuery->getBindings());
```