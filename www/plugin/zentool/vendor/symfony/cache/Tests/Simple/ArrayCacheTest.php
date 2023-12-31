<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Cache\Tests\Simple;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\ArrayCache;

/**
 * @group time-sensitive
 * @group legacy
 */
class ArrayCacheTest extends CacheTestCase
{
    public function createSimpleCache(int $defaultLifetime = 0): CacheInterface
    {
        return new ArrayCache($defaultLifetime);
    }
}
