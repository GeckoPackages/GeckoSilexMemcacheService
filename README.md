Travis test

#### GeckoPackages

# Silex Memcached service

A service provider for using `memcache` with [ Silex ](http://silex.sensiolabs.org) and the [ Memcached ](https://secure.php.net/manual/en/book.memcached.php) extension (or others).
For debugging and profiling usages it can be used with a logger.

### Requirements

PHP 5.5.9 (PHP 7 supported). Optional HHVM support >= 3.9.

### Install

The package can be installed using [Composer](https://getcomposer.org/).
Add the package to your `composer.json`.

```
"require": {
    "gecko-packages/gecko-silex-caching-service" : "^4.0"
}
```

<sub>Note: For Silex 1.x please see the 1.0 branch, use `^1.0`.</sub>

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

// usage
$app['memcache']->set('foo', 'bar');
$app['memcache']->get('foo');
$app['memcache']->delete('foo');
$app['memcache']->flush();
$app['memcache']->getPrefix();
```

## Options

The service takes the following options:
* `memcache.client`
  *string* Class to use, `memcached` (default), `mock`, or other class name.

* `memcache.prefix`
  *string* Sets the prefix for the keys to be used.\*

* `memcache.servers`
  *array/iterable* List of `memcache` servers to use.
  The array must be structured as:
  ```php
  [
      [string] 'ip address' => [int] 'port' (optional, defaults 11211),
          // ...
  ]
   ```

  When omitted one server at `127.0.0.1:11211` will be set.

* `memcache.enable_log`
  *bool* Enables logging of calls to the service. See `Logger sample` for details.

* `memcache.logger`
  *\Psr\Log\LoggerInterface* As default the service uses the `$app['logger']`,
  use this option to use an other logger. See `Logger sample` for details.

<sub>\*Note: `memcache` key length limit of 255 characters includes the prefix.

## Logger sample

Logging calls to the service can be done when a [PSR3](https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php) logger is set on the container (`$app['logger']`) or injected via the config. Enable logging by passing `true ` for the `memcache.enable_log` configuration option.

Example:
```php
$app['logger'] = $myPSR3Logger;
$app->register(new MemcachedServiceProvider(), ['memcache.enable_log' => true]);

/** @var GeckoPackages\MemcacheMock\MemcachedLogger $logger */
$logger = $app['memcache']->getLogger();

// returns '$myPSR3Logger'
$logger->getLogger();
```

Or via the config:
```php
$customLogger = $myPSR3Logger;
$app->register(
    new MemcachedServiceProvider(), [
        'memcache.enable_log' => true,
        'memcache.logger' => $customLogger; // stops using $app['logger']
    ]
);
```

<sub>Note:
Internally the `memcache` client gets wrapped for logging. Its public methods are still available, however calls as `method_exists` might fail. To get the client that is used call `getOriginalClient` on the proxy client.</sub>

## Custom name registering / multiple services

The service can be registered with an other name than the name `memcache`.
Pass the name at the `constructor` of the service and use the same name as prefix for the configuration.<br/>
The same mechanism can be used to register multiple services on an application.

Example:

```php
$app->register(new MemcachedServiceProvider('memcached'), ['memcached.prefix' => $prefix]);
$app->register(new MemcachedServiceProvider('cache2'), ['cache2.prefix' => $prefix]);

// usage
$app['memcached']->get('foo');
$app['cache2']->get('bar');
```

### License

The project is released under the MIT license, see the LICENSE file.

### Contributions

Contributions are welcome!

### Semantic Versioning

This project follows [Semantic Versioning](http://semver.org/).

<sub>Kindly note:
We do not keep a backwards compatible promise on the tests and tooling (such as document generation) of the project itself
nor the content and/or format of exception messages.</sub>
