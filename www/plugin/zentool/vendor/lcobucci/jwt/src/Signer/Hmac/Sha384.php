<?php
/**
 * This file is part of Lcobucci\JWT, a simple library to handle JWT and JWS
 *
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

declare(strict_types=1);

namespace Lcobucci\JWT\Signer\Hmac;

use Lcobucci\JWT\Signer\Hmac;

/**
 * Signer for HMAC SHA-384
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 0.1.0
 */
final class Sha384 extends Hmac
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId(): string
    {
        return 'HS384';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm(): string
    {
        return 'sha384';
    }
}
