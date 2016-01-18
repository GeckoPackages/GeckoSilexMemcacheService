<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * @author SpacePossum
 *
 * @internal
 */
final class MemcachedLoggingCompletenessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @requires extension memcached
     */
    public function testMethodLogging()
    {
        $memcachedReflection = new ReflectionClass('Memcached');
        $memcachedMethods = $this->getPublicMethods('Memcached', $memcachedReflection->getMethods());

        $loggerReflection = new ReflectionClass('GeckoPackages\Silex\Services\Caching\Clients\MemcachedLogging');
        $loggerMethods = $this->getPublicMethods('GeckoPackages\Silex\Services\Caching\Clients\MemcachedLogging', $loggerReflection->getMethods());

        $missing = array();
        foreach ($memcachedMethods as $memcachedMethod) {
            // ignore constructor
            if ('__construct' === $memcachedMethod) {
                continue;
            }

            if (!in_array($memcachedMethod, $loggerMethods, true)) {
                $missing[] = $memcachedMethod;
            }
        }

        if (count($missing) > 0) {
            $this->fail(sprintf("Missing Memcached methods on logger (%d):\n- %s", count($missing), implode("\n- ", $missing)));
        }
    }

    /**
     * @param string             $class
     * @param ReflectionMethod[] $methods
     *
     * @return string[]
     */
    private function getPublicMethods($class, array $methods)
    {
        $filtered = array();
        foreach ($methods as $method) {
            if (!$method->isPublic()) {
                continue;
            }

            if ($class !== $method->getDeclaringClass()->getName()) {
                continue;
            }

            $filtered[] = $method->getName();
        }

        sort($filtered);

        return $filtered;
    }
}
