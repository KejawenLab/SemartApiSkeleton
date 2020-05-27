<?php

declare(strict_types=1);

namespace App\Util;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Encryptor
{
    const ENCRYPTION_METHOD = 'AES-256-CBC';

    const HASH_METHOD = 'sha256';

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

        return openssl_decrypt($cipherText, self::ENCRYPTION_METHOD, $key, 0, $iv);
    }
}
