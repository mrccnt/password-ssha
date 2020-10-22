<?php declare(strict_types=1);

namespace MrCcnt;

define('PASSWORD_SSHA', 'ssha');

/**
 * Class Password
 *
 * Wrappers around PHPs password_x() functions with support for SSHA
 *
 * @link https://www.php.net/manual/en/function.password-hash.php
 * @link https://www.php.net/manual/en/function.password-verify.php
 * @link https://www.php.net/manual/en/password.constants.php
 * @link https://www.php.net/manual/en/function.password-get-info.php
 *
 * @package MrCcnt
 */
class Password
{
    const PRE = '{SSHA}';

    /**
     * @param string $pass
     * @param string|int $algo
     * @param array $options
     * @return string
     */
    public static function hash(string $pass, $algo = PASSWORD_SSHA, array $options = [])
    {
        if ($algo == PASSWORD_SSHA) {
            $salt = bin2hex(openssl_random_pseudo_bytes(6));
            return self::PRE . base64_encode(pack("H*", sha1($pass . $salt)) . $salt);
        }
        return password_hash($pass, $algo, $options);
    }

    /**
     * @param string $pass
     * @param string $hash
     * @return false
     */
    public static function verify(string $pass, string $hash)
    {
        if (self::isSSHA($hash)) {
            $dec = base64_decode(substr($hash, 6));
            return substr($dec, 0, 20) == pack("H*", sha1($pass . substr($dec, 20)));
        }
        return password_verify($pass, $hash);
    }

    /**
     * @param string $hash
     * @return array
     */
    public static function getInfo(string $hash)
    {
        if (self::isSSHA($hash)) {
            return ['algo' => 333, 'algoName' => PASSWORD_SSHA, 'options' => []];
        }
        return password_get_info($hash);
    }

    /**
     * @param string $hash
     * @param string $algo
     * @param array $options
     * @return bool
     */
    public static function needsRehash(string $hash, $algo = PASSWORD_DEFAULT, array $options = [])
    {
        if (self::isSSHA($hash) && $algo == PASSWORD_SSHA) {
            return false;
        }

        if (self::isSSHA($hash) || $algo == PASSWORD_SSHA) {
            return true;
        }

        return password_needs_rehash($hash, $algo, $options);
    }

    /**
     * @param string $hash
     * @return bool
     */
    private static function isSSHA(string $hash)
    {
        return strtoupper(substr($hash, 0, 6)) == self::PRE;
    }
}
