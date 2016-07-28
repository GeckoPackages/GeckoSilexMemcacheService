<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace GeckoPackages\Silex\Services\Caching\Clients;

use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 *  @internal
 */
final class MemcacheLoggingProxy
{
    /**
     * @var object
     */
    private $originalClient;

    /**
     * @var string
     */
    private $name;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @param object          $originalClient
     * @param LoggerInterface $logger
     * @param Stopwatch       $stopwatch
     */
    public function __construct($originalClient, LoggerInterface $logger, Stopwatch $stopwatch = null)
    {
        $this->originalClient = $originalClient;
        $this->name = substr(strrchr(get_class($originalClient), '\\'), 1);
        $this->logger = $logger;
        $this->stopwatch = null === $stopwatch ? new NullStopWatch() : $stopwatch;
    }

    /**
     * @internal
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $this->stopwatch->start($this->name, $name);
        $this->logger->debug('> '.$name, $arguments);
        $r = call_user_func_array([$this->originalClient, $name], $arguments);
        $this->logger->debug('< '.$name, [$r]);
        $this->stopwatch->stop($this->name);

        return $r;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return object
     */
    public function getOriginalClient()
    {
        return $this->originalClient;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        if ($this->originalClient instanceof Memcached) {
            return $this->__call('getPrefix', []);
        }
    }

    /**
     * @param $prefix string
     *
     * @return bool
     */
    public function setPrefix($prefix)
    {
        if ($this->originalClient instanceof Memcached) {
            return $this->__call('setPrefix', [$prefix]);
        }
    }
}
