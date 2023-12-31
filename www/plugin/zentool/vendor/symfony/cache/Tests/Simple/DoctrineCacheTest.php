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
use Symfony\Component\Cache\Simple\DoctrineCache;
use Symfony\Component\Cache\Tests\Fixtures\ArrayCache;

/**
 * @group time-sensitive
 * @group legacy
 */
class DoctrineCacheTest extends CacheTestCase
{
    protected $skippedTests = [
        'testObjectDoesNotChangeInCache' => 'ArrayCache does not use serialize/unserialize',
        'testNotUnserializable' => 'ArrayCache does not use serialize/unserialize',
    ];

    public function createSimpleCache(int $defaultLifetime = 0): CacheInterface
    {
        return new DoctrineCache(new ArrayCache($defaultLifetime), '', $defaultLifetime);
    }
}
