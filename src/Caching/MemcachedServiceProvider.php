<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace GeckoPackages\Silex\Services\Caching;

use GeckoPackages\MemcacheMock\MemcachedLogger;
use GeckoPackages\MemcacheMock\MemcachedMock;
use GeckoPackages\Silex\Services\Caching\Clients\Memcached;
use GeckoPackages\Silex\Services\Caching\Clients\MemcachedLogging;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Service for using Memcache.
 *
 * @api
 *
 * @author SpacePossum
 */
final class MemcachedServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['memcache'] = $app->share(
            function () use ($app) {
                $memcache = isset($app['memcache.client']) ? $app['memcache.client'] : 'memcached';
                switch ($memcache) {
                    case 'memcached':
                        if (!class_exists('Memcached')) {
                            throw new \RuntimeException('Cannot find class "Memcached".');
                        }

                        if (empty($app['logger'])) {
                            $memcache = new Memcached();
                        } else {
                            $memcache = new MemcachedLogging();
                            $memcache->setLogger(new MemcachedLogger($app['logger'], isset($app['stopwatch']) ? $app['stopwatch'] : null));
                        }

                        break;
                    case 'mock':
                        $memcache = new MemcachedMock();
                        if (!empty($app['logger'])) {
                            $memcache->setLogger(new MemcachedLogger($app['logger'], isset($app['stopwatch']) ? $app['stopwatch'] : null));
                        }

                        break;
                    default:
                        if (!class_exists($memcache)) {
                            throw new \UnexpectedValueException(sprintf('Cannot find class "%s" to use as cache client.', $memcache));
                        }

                        $memcache = new $memcache();
                        break;
                }

                if (isset($app['memcache.servers'])) {
                    foreach ($app['memcache.servers'] as $server) {
                        if (count($server) === 1) {
                            $server[1] = 11211; // use default port
                        }

                        $memcache->addServer($server[0], (int) $server[1]);
                    }
                } else {
                    $memcache->addServer('127.0.0.1', 11211);
                }

                if (isset($app['memcache.prefix'])) {
                    $memcache->setPrefix($app['memcache.prefix']);
                }

                return $memcache;
            }
        );
    }
}
