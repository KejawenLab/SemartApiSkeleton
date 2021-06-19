<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Util;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Encryptor
{
    private const ENCRYPTION_METHOD = 'AES-256-CBC';

    private const HASH_METHOD = 'sha256';

    public static function encrypt(string $plainText, string $key): string
    {
        $key = hash(self::HASH_METHOD, $key);
        $iv = substr(md5($key), 0, 16);

        return openssl_encrypt($plainText, self::ENCRYPTION_METHOD, $key, 0, $iv);
    }

    public static function decrypt(string $cipherText, string $key): string
    {
        $key = hash(self::HASH_METHOD, $key);
        $iv = substr(md5($key), 0, 16);
        if (false === $plainText = openssl_decrypt($cipherText, self::ENCRYPTION_METHOD, $key, 0, $iv)) {
            return '';
        }

        return $plainText;
    }

    public static function hash(string $plainText): string
    {
        return hash(self::HASH_METHOD, $plainText);
    }
}
