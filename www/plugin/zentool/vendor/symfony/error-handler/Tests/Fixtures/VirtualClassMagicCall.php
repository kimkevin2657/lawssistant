<?php

namespace Symfony\Component\ErrorHandler\Tests\Fixtures;

/**
 * @method string magicMethod()
 * @method static string staticMagicMethod()
 */
class VirtualClassMagicCall
{
    public static function __callStatic($name, $arguments)
    {
    }

    public function __call($name, $arguments)
    {
    }
}
