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

            if ($this->shouldLog($app, $this->name)) {
                $memcache = new MemcacheLoggingProxy(
                    $memcache,
                    $app['logger'],
                    class_exists('Symfony\Component\Stopwatch\Stopwatch') && isset($app['stopwatch']) && $app['stopwatch'] instanceof Stopwatch ? $app['stopwatch'] : null
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
     * @return bool
     */
    private function shouldLog(Container $app, $name)
    {
        return
            isset($app[$name.'.enable_log'])
            && true === $app[$name.'.enable_log']
            && !empty($app['logger'])
            && interface_exists('Psr\Log\LoggerInterface')
            && $app['logger'] instanceof \Psr\Log\LoggerInterface
        ;
    }
}
