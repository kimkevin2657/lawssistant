<?php

$container->loadFromExtension('framework', [
    'cache' => [
        'pools' => [
            'cache.foo' => [
                'adapter' => 'cache.adapter.apcu',
                'default_lifetime' => 30,
            ],
            'cache.bar' => [
                'adapter' => 'cache.adapter.doctrine',
                'default_lifetime' => 5,
                'provider' => 'app.doctrine_cache_provider',
            ],
            'cache.baz' => [
                'adapter' => 'cache.adapter.filesystem',
                'default_lifetime' => 7,
            ],
            'cache.foobar' => [
                'adapter' => 'cache.adapter.psr6',
                'default_lifetime' => 10,
                'provider' => 'app.cache_pool',
            ],
            'cache.def' => [
                'default_lifetime' => 11,
            ],
            'cache.chain' => [
                'default_lifetime' => 12,
                'adapter' => [
                    'cache.adapter.array',
                    'cache.adapter.filesystem',
                    'redis://foo' => 'cache.adapter.redis',
                ],
            ],
        ],
    ],
]);
