#### GeckoPackages

# Silex Memcached service

A service provider for using `memcache` with [ Silex ](http://silex.sensiolabs.org) and the [ Memcached ](https://secure.php.net/manual/en/book.memcached.php) extension.
For debugging and profiling usages it can be used with a logger.

### Requirements

PHP 5.4.0

### Install

The package can be installed using [ Composer ](https://getcomposer.org/).
Add the package to your `composer.json`.

```
"require": {
    "gecko-packages/gecko-silex-caching-service" : "1.*"
}
```

## Example

```php
// register
$settings =
    array(
        'memcache.prefix' => $prefix,
        'memcache.servers' =>
        array(
            array('127.0.0.1', 11212),
            array('127.0.0.2'),
        )
);
$app->register(new MemcachedServiceProvider(), $settings);

// use
$app['memcache']->set('foo', 'bar');
$app['memcache']->get('foo');
$app['memcache']->delete('foo');
$app['memcache']->flush();
$app['memcache']->getPrefix();
```

## Options

The service takes the following options:
* `memcache.client`
   `memcached` (default), `mock`, or a class name.

* `memcache.prefix`
   Set the prefix for the keys to be used.

* `memcache.servers`
   List of memcache servers to use.
   The array must be structured as:
   ```php
    [
        [string] 'ip address' => [int] 'port' (optional, defaults 11211),
        // ...
    ]
    ```

   When omitted one server at `127.0.0.1:11211` will be configured.

### Logger sample

When using the `memcached` or `mock` class and have a logger (`$app['logger']`) set a logger will be added to the Memcache service.
Example:
```php
$app['logger'] = $myPSR3Logger;
$app->register(new MemcachedServiceProvider(), $settings);

/** @var GeckoPackages\MemcacheMock\MemcachedLogger $logger */
$logger = $app['memcache']->getLogger();

// returns '$myPSR3Logger'
$logger->getLogger();
```
## Custom name registering / multiple services

You can register the service using a name other than the default name `memcache`.
The same method can be used to register multiple services on your application.
Pass the name at the constructor of the service and use the same name as prefix for the configuration.
For example:

```php

$app->register(new MemcachedServiceProvider('memcached'), array('memcached.prefix' => $prefix));

// usage
$app['memcached']->get('foo');

```

### License

The project is released under the MIT license, see the LICENSE file.
