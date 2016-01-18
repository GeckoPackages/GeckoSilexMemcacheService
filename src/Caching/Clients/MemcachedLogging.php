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

use GeckoPackages\MemcacheMock\MemcachedLogger;

/**
 * @api
 *
 * @author SpacePossum
 */
class MemcachedLogging extends Memcached
{
    /**
     * @var MemcachedLogger
     */
    private $logger;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param MemcachedLogger $logger
     */
    public function setLogger(MemcachedLogger $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('add', array('key' => $key, 'value' => $value, 'expiration' => $expiration));
        }

        $result = parent::add($key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function addByKey($server_key, $key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('addByKey', array('server_key' => $server_key, 'key' => $key, 'value' => $value, 'expiration' => $expiration));
        }

        $result = parent::addByKey($server_key, $key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function addServer($host, $port, $weight = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('addServer', array('host' => $host, 'port' => $port, 'weight' => $weight));
        }

        $result = parent::addServer($host, $port, $weight);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function addServers(array $servers)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('addServers', array('servers' => $servers));
        }

        $result = parent::addServers($servers);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function append($key, $value)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('append', array('key' => $key, 'value' => $value));
        }

        $result = parent::append($key, $value);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function appendByKey($server_key, $key, $value)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('appendByKey', array('server_key' => $server_key, 'key' => $key, 'value' => $value));
        }

        $result = parent::appendByKey($server_key, $key, $value);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function cas($cas_token, $key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('cas', array('cas_token' => $cas_token, 'key' => $key, 'value' => $value, 'expiration ' => $expiration));
        }

        $result = parent::cas($cas_token, $key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function casByKey($cas_token, $server_key, $key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('casByKey', array('cas_token' => $cas_token, 'server_key' => $server_key, 'key' => $key, 'value' => $value, 'expiration' => $expiration));
        }

        $result = parent::casByKey($cas_token, $server_key, $key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('decrement', array('key' => $key, 'offset' => $offset, 'initial_value' => $initial_value, 'expiry' => $expiry));
        }

        $result = parent::decrement($key, $offset, $initial_value, $expiry);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function decrementByKey($server_key, $key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('decrementByKey', array('server_key' => $server_key, 'key' => $key, 'offset' => $offset, 'initial_value' => $initial_value, 'expiry' => $expiry));
        }

        $result = parent::decrementByKey($server_key, $key, $offset, $initial_value, $expiry);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, $time = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('delete', array('key' => $key, 'time' => $time));
        }

        $result = parent::delete($key, $time);
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function deleteByKey($server_key, $key, $time = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('deleteByKey', array('server_key' => $server_key, 'key' => $key, 'time' => $time));
        }

        $result = parent::deleteByKey($server_key, $key, $time);
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function deleteMulti(array $keys, $time = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('deleteMulti', array('keys' => $keys, 'time' => $time));
        }

        $result = parent::deleteMulti($keys, $time);
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function deleteMultiByKey($server_key, array $keys, $time = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('deleteMultiByKey', array('server_key' => $server_key, 'keys' => $keys, 'time' => $time));
        }

        $result = parent::deleteMultiByKey($server_key, $keys, $time);
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function fetch()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('fetch');
        }

        $result = parent::fetch();
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function fetchAll()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('fetchAll');
        }

        $result = parent::fetchAll();
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function flush($delay = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('flush', array('delay' => $delay));
        }

        $result = parent::flush($delay);
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function get($key, callable $cache_cb = null, &$cas_token = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('get', array('key' => $key, 'cache_cb' => null !== $cache_cb, 'cas_token' => null !== $cas_token));
        }

        $result = parent::get($key, $cache_cb, $cas_token);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllKeys()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getAllKeys');
        }

        $result = parent::getAllKeys();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getByKey($server_key, $key, callable $cache_cb = null, &$cas_token = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getByKey', array('server_key' => $server_key, 'key' => $key, 'cache_cb' => null !== $cache_cb, 'cas_token' => null !== $cas_token));
        }

        $result = parent::getByKey($server_key, $key, $cache_cb, $cas_token);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getDelayed(array $keys, $with_cas = null, callable $value_cb = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getDelayed', array('keys' => $keys, 'with_cas' => $with_cas, 'value_cb' => null !== $value_cb));
        }

        $result = parent::getDelayed($keys, $with_cas, $value_cb);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getDelayedByKey($server_key, array $keys, $with_cas = null, callable $value_cb = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getDelayedByKey', array('server_key' => $server_key, 'keys' => $keys, 'with_cas' => $with_cas, 'value_cb' => null !== $value_cb));
        }

        $result = parent::getDelayedByKey($server_key, $keys, $with_cas, $value_cb);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getMulti(array $keys, array &$cas_tokens = null, $flags = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getMulti', array('keys' => $keys, 'cas_tokens' => null !== $cas_tokens, 'flags' => $flags));
        }

        $result = parent::getMulti($keys, $cas_tokens, $flags);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiByKey($server_key, array $keys, &$cas_tokens = null, $flags = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getMultiByKey', array('server_key' => $server_key, 'keys' => $keys, 'cas_tokens' => null !== $cas_tokens, 'flags' => $flags));
        }

        $result = parent::getMultiByKey($server_key, $keys, $cas_tokens, $flags);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($option)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getOption', array('option' => $option));
        }

        $result = parent::getOption($option);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultCode()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getResultCode');
        }

        $result = parent::getResultCode();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultMessage()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getResultMessage');
        }

        $result = parent::getResultMessage();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getServerByKey($server_key)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getServerByKey', array('server_key' => $server_key));
        }

        $result = parent::getServerByKey($server_key);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getServerList()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getServerList');
        }

        $result = parent::getServerList();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getStats()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getStats');
        }

        $result = parent::getStats();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('getVersion');
        }

        $result = parent::getVersion();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('increment', array('key' => $key, 'offset' => $offset, 'initial_value' => $initial_value, 'expiry' => $expiry));
        }

        $result = parent::increment($key, $offset, $initial_value, $expiry);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByKey($server_key, $key, $offset = 1, $initial_value = 0, $expiry = 0)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('incrementByKey', array('server_key' => $server_key, 'key' => $key, 'offset' => $offset, 'initial_value' => $initial_value, 'expiry' => $expiry));
        }

        $result = parent::incrementByKey($server_key, $key, $offset, $initial_value, $expiry);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isPersistent()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('isPersistent');
        }

        $result = parent::isPersistent();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isPristine()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('isPristine');
        }

        $result = parent::isPristine();
        $this->stopMethod();

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function prepend($key, $value)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('prepend', array('key' => $key, 'value' => $value));
        }

        $result = parent::prepend($key, $value);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function prependByKey($server_key, $key, $value)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('prependByKey', array('server_key' => $server_key, 'key' => $key, 'value' => $value));
        }

        $result = parent::prependByKey($server_key, $key, $value);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function quit()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('quit');
        }

        $result = parent::quit();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('replace', array('key' => $key, 'value' => $value, 'expiration' => $expiration));
        }

        $result = parent::replace($key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function replaceByKey($server_key, $key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('replaceByKey', array('server_key' => $server_key, 'key' => $key, 'value' => $value, 'expiration' => $expiration));
        }

        $result = parent::replaceByKey($server_key, $key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function resetServerList()
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('resetServerList');
        }

        $result = parent::resetServerList();
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('set', array('key' => $key, 'value' => $value, 'expiration' => $expiration));
        }

        $result = parent::set($key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setByKey($server_key, $key, $value, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('setByKey', array('server_key' => $server_key, 'key' => $key, 'value' => $value, 'expiration' => $expiration));
        }

        $result = parent::setByKey($server_key, $key, $value, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setMulti(array $items, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('setMulti', array('items' => $items, 'expiration' => $expiration));
        }

        $result = parent::setMulti($items, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiByKey($server_key, array $items, $expiration = null)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('setMultiByKey', array('server_key' => $server_key, 'items' => $items, 'expiration' => $expiration));
        }

        $result = parent::setMultiByKey($server_key, $items, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($option, $value)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('setOption', array('option' => $option, 'value' => $value));
        }

        $result = parent::setOption($option, $value);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('setOptions', array('options' => $options));
        }

        $result = parent::setOptions($options);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function touch($key, $expiration)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('touch', array('key' => $key, 'expiration' => $expiration));
        }

        $result = parent::touch($key, $expiration);
        $this->stopMethod();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function touchByKey($server_key, $key, $expiration)
    {
        if (null !== $this->logger) {
            $this->logger->startMethod('touchByKey', array('server_key' => $server_key, 'key' => $key, 'expiration' => $expiration));
        }

        $result = parent::touchByKey($server_key, $key, $expiration);
        $this->stopMethod();

        return $result;
    }

    private function stopMethod()
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->stopMethod();
    }
}
