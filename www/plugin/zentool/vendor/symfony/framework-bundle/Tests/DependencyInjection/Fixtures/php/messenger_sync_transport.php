<?php

$container->loadFromExtension('framework', [
    'messenger' => [
        'transports' => [
            'amqp' => 'amqp://localhost/%2f/messages',
            'sync' => 'sync://',
        ],
        'routing' => [
            'Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Messenger\DummyMessage' => ['amqp'],
            'Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Messenger\SecondMessage' => ['sync'],
            'Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Messenger\FooMessage' => ['amqp', 'sync'],
        ],
    ],
]);
