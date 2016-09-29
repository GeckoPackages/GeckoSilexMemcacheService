<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use GeckoPackages\MemcacheMock\MemcachedMock;
use GeckoPackages\Silex\Services\Caching\Clients\Memcached;
use GeckoPackages\Silex\Services\Caching\Clients\MemcacheLoggingProxy;
use GeckoPackages\Silex\Services\Caching\MemcachedServiceProvider;
use Silex\Application;

/**
 * @internal
 *
 * @author SpacePossum
 */
final class MemcachedServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @requires extension memcached
     */
    public function testDefaults()
    {
        $name = 'UnitTest';
        $app = new Application();
        $app['debug'] = true;
        $app['logger'] = new TestLogger();
        $app->register(new MemcachedServiceProvider(), ['memcache.prefix' => $name, 'memcache.enable_log' => true]);

        /** @var TestLogger $logger */
        $logger = $app['memcache']->getLogger();
        $this->assertInstanceOf(Psr\Log\LoggerInterface::class, $logger);

        /** @var array $calls */
        $calls = $logger->getDebugLog();

        $this->assertInternalType('array', $calls);
        $this->assertCount(4, $calls);

        $this->assertSame('> Memcached::addServer', $calls[0][0]);

        $this->assertSame(
            ['127.0.0.1', 11211],
            array_slice($calls[0][1], 0, 2, true)
        );

        $this->assertSame('< Memcached::addServer', $calls[1][0]);
        $this->assertTrue($calls[1][1][0]);

        $this->assertSame('> Memcached::setPrefix', $calls[2][0]);
        $this->assertSame([$name], $calls[2][1]);

        $this->assertSame('< Memcached::setPrefix', $calls[3][0]);
        $this->assertSame([true], $calls[3][1]);

        $logger->resetDebugLog();

        $prefix = $app['memcache']->getOption(\Memcached::OPT_PREFIX_KEY);

        $calls = $logger->getDebugLog();
        $this->assertCount(2, $calls);
        $this->assertSame('> Memcached::getOption', $calls[0][0]);
        $this->assertSame([\Memcached::OPT_PREFIX_KEY], $calls[0][1]);
        $this->assertSame('< Memcached::getOption', $calls[1][0]);
        $this->assertSame([$name], $calls[1][1]);

        $this->assertSame($name, $prefix);
        $this->assertSame($name, $app['memcache']->getPrefix());
        $servers = $app['memcache']->getServerList();
        $this->assertInternalType('array', $servers);
        $this->assertCount(1, $servers);
        $server = $servers[0];
        $this->assertArrayHasKey('host', $server);
        $this->assertSame('127.0.0.1', $server['host']);
        $this->assertArrayHasKey('port', $server);
        $this->assertSame(11211, $server['port']);
    }

    /**
     * @requires extension memcached
     */
    public function testConfigSetting()
    {
        $prefix = 'UnitTest2';
        $app = new Application();
        $app['name'] = 'UnitTest';
        $app->register(
            new MemcachedServiceProvider(),
            [
                'memcache.prefix' => $prefix,
                'memcache.servers' => [
                    ['127.0.0.2', 11212],
                    ['127.0.0.3', '11213'],
                    ['127.0.0.4'],
                ],
            ]
        );
        $this->runCacheTest($app, $prefix);
    }

    /**
     * @requires extension memcached
     */
    public function testConfigSettingLater()
    {
        $prefix = 'UnitTest3';
        $app = new Application();
        $app['name'] = 'UnitTest';
        $app->register(new MemcachedServiceProvider());
        $app['memcache.prefix'] = $prefix;
        $app['memcache.servers'] =
            [
                ['127.0.0.2', 11212],
                ['127.0.0.3', '11213'],
                ['127.0.0.4'],
            ];
        $this->runCacheTest($app, $prefix);
    }

    public function testCustomClient()
    {
        $app = new Application();
        $app->register(new MemcachedServiceProvider(), ['memcache.client' => 'TestCustomCacheClient']);
        $this->assertInstanceOf(TestCustomCacheClient::class, $app['memcache']);
        $app['memcache']->addServer();
        $this->assertSame(2, $app['memcache']->getAddServerCallCount());
    }

    /**
     * @param bool  $expected
     * @param bool  $setAppLogger
     * @param array $configuration
     *
     * @requires extension memcached
     * @dataProvider provideLoggerByConfiguration
     */
    public function testLoggerByConfiguration($setAppLogger, $expected, array $configuration)
    {
        $app = new Application();
        if($setAppLogger){
            $logger = new TestLogger();
            $app['logger'] = $logger;
        }
        $app->register(new MemcachedServiceProvider(), $configuration);
        if ($expected) {
            $this->assertInstanceOf(MemcacheLoggingProxy::class, $app['memcache']);
            $this->assertInstanceOf(MemcachedMock::class, $app['memcache']->getOriginalClient());
        } else {
            $this->assertInstanceOf(MemcachedMock::class, $app['memcache']);
            $this->assertNull($app['memcache']->getLogger());
        }
    }

    public function provideLoggerByConfiguration()
    {
        return [
            // add logger to $app['logger'] before testing
            [true, false, ['memcache.client' => 'mock']],
            [true, true, ['memcache.client' => 'mock', 'memcache.enable_log' => false]],
            [true, true, ['memcache.client' => 'mock', 'memcache.enable_log' => true]],
            [true, false, ['memcache.client' => 'mock', 'memcache.logger' => new TestLogger()]],

            // do not addd logger to $app['logger'] before testing
            [false, false, ['memcache.client' => 'mock', 'memcache.logger' => new TestLogger()]],
            [false, true, [
                'memcache.client' => 'mock',
                'memcache.enable_log' => false,
                'memcache.logger' => new TestLogger()
            ]],
            [false, true, [
                'memcache.client' => 'mock',
                'memcache.enable_log' => true,
                'memcache.logger' => new TestLogger()
            ]]
        ];
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessageRegExp #^Cannot find class "\\Foo\\Bar" to use as memcache client.$#
     */
    public function testExceptionMissingCustomClient()
    {
        $app = new Application();
        $app->register(new MemcachedServiceProvider(), ['memcache.client' => '\Foo\Bar']);
        $app['memcache']->getServerList();
    }

    public function testMockClient()
    {
        $app = new Application();
        $app->register(new MemcachedServiceProvider(), ['memcache.client' => 'mock']);
        $this->assertInstanceOf(MemcachedMock::class, $app['memcache']);
    }

    public function testMockWithLoggerClient()
    {
        $logger = new TestLogger();
        $app = new Application();
        $app['logger'] = $logger;
        $app->register(new MemcachedServiceProvider(), ['memcache.client' => 'mock', 'memcache.enable_log' => true]);
        $this->assertInstanceOf(MemcacheLoggingProxy::class, $app['memcache']);
        $this->assertInstanceOf(MemcachedMock::class, $app['memcache']->getOriginalClient());
    }

    /**
     * @requires extension memcached
     */
    public function testNoLoggerAtDefault()
    {
        $app = new Application();
        $app['logger'] = false;
        $app->register(new MemcachedServiceProvider());
        $this->assertInstanceof(Memcached::class, $app['memcache']);
        $this->assertSame('', $app['memcache']->getPrefix());
    }

    /**
     * @requires extension memcached
     */
    public function testNoLoggerIfCustomLogger()
    {
        $app = new Application();
        $app['logger'] = new \stdClass();
        $app->register(new MemcachedServiceProvider());
        $this->assertInstanceof(Memcached::class, $app['memcache']);
        $this->assertSame('', $app['memcache']->getPrefix());
    }

    public function testServiceName()
    {
        $app = new Application();

        $name1 = 'memcached';
        $prefix1 = 'prefix1';
        $service1 = new MemcachedServiceProvider($name1);
        $app->register($service1, [$name1.'.client' => 'mock', $name1.'.prefix' => $prefix1]);

        $name2 = 'cache';
        $prefix2 = 'prefix2';
        $service2 = new MemcachedServiceProvider($name2);
        $app->register($service2, [$name2.'.client' => 'mock', $name2.'.prefix' => $prefix2]);

        $this->assertFalse(isset($app['memcache']));

        $this->assertTrue(isset($app[$name1]));
        $this->assertInstanceOf(MemcachedMock::class, $app[$name1]);

        $this->assertTrue(isset($app[$name2]));
        $this->assertInstanceOf(MemcachedMock::class, $app[$name2]);

        $app[$name1]->set('foo', 'bar');

        $this->assertSame('bar', $app[$name1]->get('foo'));
        $this->assertFalse($app[$name2]->get('foo'));

        $this->assertSame($prefix1, $app[$name1]->getPrefix());
        $this->assertSame($prefix2, $app[$name2]->getPrefix());
    }

    private function runCacheTest(Application $app, $prefix)
    {
        $prefixReadBack = $app['memcache']->getOption(\Memcached::OPT_PREFIX_KEY);
        $this->assertSame($prefix, $prefixReadBack);
        $this->assertSame($prefix, $app['memcache']->getPrefix());
        $servers = $app['memcache']->getServerList();
        $this->assertInternalType('array', $servers);
        $this->assertCount(3, $servers);
        $server = $servers[0];
        $this->assertArrayHasKey('host', $server);
        $this->assertSame('127.0.0.2', $server['host']);
        $this->assertArrayHasKey('port', $server);
        $this->assertSame(11212, $server['port']);
        $server = $servers[1];
        $this->assertArrayHasKey('host', $server);
        $this->assertSame('127.0.0.3', $server['host']);
        $this->assertArrayHasKey('port', $server);
        $this->assertSame(11213, $server['port']);
        $server = $servers[2];
        $this->assertArrayHasKey('host', $server);
        $this->assertSame('127.0.0.4', $server['host']);
        $this->assertArrayHasKey('port', $server);
        $this->assertSame(11211, $server['port']);
    }
}

/**
 * @internal
 *
 * @author SpacePossum
 */
final class TestCustomCacheClient
{
    private $addServerCallCount = 0;

    public function addServer()
    {
        ++$this->addServerCallCount;
    }

    public function getAddServerCallCount()
    {
        return $this->addServerCallCount;
    }
}
