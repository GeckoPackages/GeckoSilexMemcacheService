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

use GeckoPackages\Silex\Services\Caching\Clients\Memcached;
use GeckoPackages\Silex\Services\Caching\Clients\MemcacheLoggingProxy;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

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
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name = 'memcache')
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $name = $this->name;
        $app[$name] = function ($app) use ($name) {
            $memcache = isset($app[$name.'.client']) ? $app[$name.'.client'] : 'memcached';
            switch ($memcache) {
                case 'memcached':
                    if (!class_exists('Memcached')) {
                        throw new \RuntimeException('Cannot find class "Memcached".');
                    }

                    $memcache = new Memcached();

                    break;
                case 'mock':
                    $memcache = new \GeckoPackages\MemcacheMock\MemcachedMock();

                    break;
                default:
                    if (!class_exists($memcache)) {
                        throw new \UnexpectedValueException(sprintf('Cannot find class "%s" to use as memcache client.', $memcache));
                    }

                    $memcache = new $memcache();
                    break;
            }

            if (null !== $logger = $this->getLogger($app, $name)) {
                $memcache = new MemcacheLoggingProxy(
                    $memcache,
                    $logger,
                    isset($app['stopwatch']) && class_exists('Symfony\Component\Stopwatch\Stopwatch') && $app['stopwatch'] instanceof Stopwatch ? $app['stopwatch'] : null
                );
            }

            if (isset($app[$name.'.servers'])) {
                foreach ($app[$name.'.servers'] as $server) {
                    if (count($server) === 1) {
                        $server[1] = 11211; // use default port
                    }

                    $memcache->addServer($server[0], (int) $server[1]);
                }
            } else {
                $memcache->addServer('127.0.0.1', 11211);
            }

            if (isset($app[$name.'.prefix']) && method_exists($memcache, 'setPrefix')) {
                $memcache->setPrefix($app[$name.'.prefix']);
            }

            return $memcache;
        };
    }

    /**
     * @param Container $app
     * @param string    $name
     *
     * @return LoggerInterface|null
     */
    private function getLogger(Container $app, $name)
    {
        if (!isset($app[$name.'.enable_log']) || !interface_exists('Psr\Log\LoggerInterface')) {
            return null;
        }

        $logger = null;
        if (isset($app[$name.'.logger'])) {
            $logger = $app[$name.'.logger'];
        } elseif (!empty($app['logger'])) {
            $logger = $app['logger'];
        }

        return $logger instanceof LoggerInterface ? $logger : null;
    }
}
