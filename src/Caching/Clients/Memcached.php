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

/**
 * @api
 *
 * @author SpacePossum
 */
class Memcached extends \Memcached
{
    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->getOption(\Memcached::OPT_PREFIX_KEY);
    }

    /**
     * @param $prefix string
     *
     * @return bool
     */
    public function setPrefix($prefix)
    {
        return $this->setOption(\Memcached::OPT_PREFIX_KEY, $prefix);
    }
}
