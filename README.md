#### GeckoPackages

# Silex Memcached service

A service provider for using `memcache` with [ Silex ](http://silex.sensiolabs.org) and the [ Memcached ](https://secure.php.net/manual/en/book.memcached.php) extension.
For debugging and profiling usages it can be used with a logger.
For Silex 1.x please see the 1.0 branch.

### Requirements

PHP 5.5.9 (PHP7 supported)

### Install

The package can be installed using [ Composer ](https://getcomposer.org/).
Add the package to your `composer.json`.

```
"require": {
    "gecko-packages/gecko-silex-caching-service" : "^3.*"
}
```

Note: use the `^1.*` line for Silex `1.*`.

## Example

```php
// register
$settings = [
        'memcache.prefix' => $prefix,
        'memcache.servers' => [
            ['127.0.0.1', 11212],
            ['127.0.0.2'],
        ]
];
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

* `memcache.enable_log`
   Enables logging of calls to the service, see below for details.

### Logger sample

Logging calls to the service can be done when a [PSR3](https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php) logger is set on the container (`$app['logger']`). 
Enable logging by passing the `memcache.enable_log` configuration option.

Example:
```php
$app['logger'] = $myPSR3Logger;
$app->register(new MemcachedServiceProvider(), ['memcache.enable_log' => true]);

/** @var GeckoPackages\MemcacheMock\MemcachedLogger $logger */
$logger = $app['memcache']->getLogger();

// returns '$myPSR3Logger'
$logger->getLogger();
```
Note:
Because internally the `memcache` client is wrapped its public methods are still available. 
However calls like `method_exists` might fail. To get the original client that is in use call `getOriginalClient` on the proxy client.

## Custom name registering / multiple services

You can register the service using a name other than the default name `memcache`.
The same method can be used to register multiple services on your application.
Pass the name at the constructor of the service and use the same name as prefix for the configuration.
For example:

```php

$app->register(new MemcachedServiceProvider('memcached'), ['memcached.prefix' => $prefix]);
$app->register(new MemcachedServiceProvider('cache2'), ['cache2.prefix' => $prefix]);

// usage
$app['memcached']->get('foo');
$app['cache2']->get('bar');

```

### License

The project is released under the MIT license, see the LICENSE file.
