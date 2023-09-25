<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Secrets;

/**
 * @author Tobias Schultze <http://tobion.de>
 * @author Jérémy Derussé <jeremy@derusse.com>
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class SodiumVault extends AbstractVault
{
    private $encryptionKey;
    private $decryptionKey;
    private $pathPrefix;

    /**
     * @param string|object|null $decryptionKey A string or a stringable object that defines the private key to use to decrypt the vault
     *                                          or null to store generated keys in the provided $secretsDir
     */
    public function __construct(string $secretsDir, $decryptionKey = null)
    {
        if (!\function_exists('sodium_crypto_box_seal')) {
            throw new \LogicException('The "sodium" PHP extension is required to deal with secrets. Alternatively, try running "composer require paragonie/sodium_compat" if you cannot enable the extension."');
        }

        if (null !== $decryptionKey && !\is_string($decryptionKey) && !(\is_object($decryptionKey) && method_exists($decryptionKey, '__toString'))) {
            throw new \TypeError(sprintf('Decryption key should be a string or an object that implements the __toString() method, %s given.', \gettype($decryptionKey)));
        }

        if (!is_dir($secretsDir) && !@mkdir($secretsDir, 0777, true) && !is_dir($secretsDir)) {
            throw new \RuntimeException(sprintf('Unable to create the secrets directory (%s)', $secretsDir));
        }

        $this->pathPrefix = rtrim(strtr($secretsDir, '/', \DIRECTORY_SEPARATOR), \DIRECTORY_SEPARATOR).\DIRECTORY_SEPARATOR.basename($secretsDir).'.';
        $this->decryptionKey = $decryptionKey;
    }

    public function generateKeys(bool $override = false): bool
    {
        $this->lastMessage = null;

        if (null === $this->encryptionKey && '' !== $this->decryptionKey = (string) $this->decryptionKey) {
            throw new \LogicException('Cannot generate keys when a decryption key has been provided while instantiating the vault.');
        }

        try {
            $this->loadKeys();
        } catch (\LogicException $e) {
            // ignore failures to load keys
        }

        if ('' !== $this->decryptionKey && !file_exists($this->pathPrefix.'sodium.encrypt.public')) {
            $this->export('sodium.encrypt.public', $this->encryptionKey);
        }

        if (!$override && null !== $this->encryptionKey) {
            $this->lastMessage = sprintf('Sodium keys already exist at "%s*.{public,private}" and won\'t be overridden.', $this->getPrettyPath($this->pathPrefix));

            return false;
        }

        $this->decryptionKey = sodium_crypto_box_keypair();
        $this->encryptionKey = sodium_crypto_box_publickey($this->decryptionKey);

        $this->export('sodium.encrypt.public', $this->encryptionKey);
        $this->export('sodium.decrypt.private', $this->decryptionKey);

        $this->lastMessage = sprintf('Sodium keys have been generated at "%s*.{public,private}".', $this->getPrettyPath($this->pathPrefix));

        return true;
    }

    public function seal(string $name, string $value): void
    {
        $this->lastMessage = null;
        $this->validateName($name);
        $this->loadKeys();
        $this->export($name.'.'.substr_replace(md5($name), '.sodium', -26), sodium_crypto_box_seal($value, $this->encryptionKey ?? sodium_crypto_box_publickey($this->decryptionKey)));

        $list = $this->list();
        $list[$name] = null;
        uksort($list, 'strnatcmp');
        file_put_contents($this->pathPrefix.'sodium.list', sprintf("<?php\n\nreturn %s;\n", var_export($list, true), LOCK_EX));

        $this->lastMessage = sprintf('Secret "%s" encrypted in "%s"; you can commit it.', $name, $this->getPrettyPath(\dirname($this->pathPrefix).\DIRECTORY_SEPARATOR));
    }

    public function reveal(string $name): ?string
    {
        $this->lastMessage = null;
        $this->validateName($name);

        if (!file_exists($file = $this->pathPrefix.$name.'.'.substr_replace(md5($name), '.sodium', -26))) {
            $this->lastMessage = sprintf('Secret "%s" not found in "%s".', $name, $this->getPrettyPath(\dirname($this->pathPrefix).\DIRECTORY_SEPARATOR));

            return null;
        }

        $this->loadKeys();

        if ('' === $this->decryptionKey) {
            $this->lastMessage = sprintf('Secrets cannot be revealed as no decryption key was found in "%s".', $this->getPrettyPath(\dirname($this->pathPrefix).\DIRECTORY_SEPARATOR));

            return null;
        }

        if (false === $value = sodium_crypto_box_seal_open(include $file, $this->decryptionKey)) {
            $this->lastMessage = sprintf('Secrets cannot be revealed as the wrong decryption key was provided for "%s".', $this->getPrettyPath(\dirname($this->pathPrefix).\DIRECTORY_SEPARATOR));

            return null;
        }

        return $value;
    }

    public function remove(string $name): bool
    {
        $this->lastMessage = null;
        $this->validateName($name);

        if (!file_exists($file = $this->pathPrefix.$name.'.'.substr_replace(md5($name), '.sodium', -26))) {
            $this->lastMessage = sprintf('Secret "%s" not found in "%s".', $name, $this->getPrettyPath(\dirname($this->pathPrefix).\DIRECTORY_SEPARATOR));

            return false;
        }

        $list = $this->list();
        unset($list[$name]);
        file_put_contents($this->pathPrefix.'sodium.list', sprintf("<?php\n\nreturn %s;\n", var_export($list, true), LOCK_EX));

        $this->lastMessage = sprintf('Secret "%s" removed from "%s".', $name, $this->getPrettyPath(\dirname($this->pathPrefix).\DIRECTORY_SEPARATOR));

        return @unlink($file) || !file_exists($file);
    }

    public function list(bool $reveal = false): array
    {
        $this->lastMessage = null;

        if (!file_exists($file = $this->pathPrefix.'sodium.list')) {
            return [];
        }

        $secrets = include $file;

        if (!$reveal) {
            return $secrets;
        }

        foreach ($secrets as $name => $value) {
            $secrets[$name] = $this->reveal($name);
        }

        return $secrets;
    }

    private function loadKeys(): void
    {
        if (null !== $this->encryptionKey || '' !== $this->decryptionKey = (string) $this->decryptionKey) {
            return;
        }

        if (file_exists($this->pathPrefix.'sodium.decrypt.private')) {
            $this->decryptionKey = (string) include $this->pathPrefix.'sodium.decrypt.private';
        }

        if (file_exists($this->pathPrefix.'sodium.encrypt.public')) {
            $this->encryptionKey = (string) include $this->pathPrefix.'sodium.encrypt.public';
        } elseif ('' !== $this->decryptionKey) {
            $this->encryptionKey = sodium_crypto_box_publickey($this->decryptionKey);
        } else {
            throw new \LogicException(sprintf('Encryption key not found in "%s".', \dirname($this->pathPrefix)));
        }
    }

    private function export(string $file, string $data): void
    {
        $name = basename($this->pathPrefix.$file);
        $data = str_replace('%', '\x', rawurlencode($data));
        $data = sprintf("<?php // %s on %s\n\nreturn \"%s\";\n", $name, date('r'), $data);

        if (false === file_put_contents($this->pathPrefix.$file, $data, LOCK_EX)) {
            $e = error_get_last();
            throw new \ErrorException($e['message'] ?? 'Failed to write secrets data.', 0, $e['type'] ?? E_USER_WARNING);
        }
    }
}
