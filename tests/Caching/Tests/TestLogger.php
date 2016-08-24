<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Psr\Log\LoggerInterface;

/**
 * Test logger for unit testing.
 *
 * @internal
 *
 * @author SpacePossum
 */
final class TestLogger implements LoggerInterface
{
    private $debugLog = [];

    /**
     * @return array<array<string, array<string, mixed>>>
     */
    public function getDebugLog()
    {
        return $this->debugLog;
    }

    public function resetDebugLog()
    {
        $this->debugLog = [];
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        $this->debugLog[] = [$message, $context];
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
    }
}
