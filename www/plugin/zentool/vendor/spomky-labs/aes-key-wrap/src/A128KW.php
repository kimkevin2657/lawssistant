<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace AESKW;

final class A128KW
{
    use AESKW;

    protected static function getMethod(): string
    {
        return 'aes-128-ecb';
    }
}
