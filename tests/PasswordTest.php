<?php declare(strict_types=1);

namespace MrCcnt\Tests;

use MrCcnt\Password;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class PasswordTest
 * @package MrCcnt\Tests
 */
class PasswordTest extends TestCase
{
    // Value has been generated via native password_hash()
    const FIXED_BCRYPT = '$2y$10$TNRrEVSDJosbWCs7yqP17.Qy4RzrfYJguSjyukgZKJjfu0OOS5Coa';

    // Value has been generated via OpenLDAP
    const FIXED_SSHA = '{SSHA}sqp1cTKv6Z/OndKNT3fPtU+YyYQQH+b47aPCOQ==';

    /**
     * Test hardcoded/known bcrypt hash
     */
    public function testBcryptPregeneratedTrue()
    {
        Assert::assertTrue(Password::verify('test', self::FIXED_BCRYPT));
    }

    /**
     * Test hardcoded/known bcrypt hash
     */
    public function testBcryptPregeneratedFalse()
    {
        Assert::assertFalse(Password::verify('testx', self::FIXED_BCRYPT));
    }

    /**
     * Test generated bcrypt hash
     */
    public function testBcryptGeneratedTrue()
    {
        $hash = Password::hash('test', PASSWORD_BCRYPT);
        Assert::assertTrue(Password::verify('test', $hash));
    }

    /**
     * Test hardcoded/known ssha hash
     */
    public function testSshaPregeneratedTrue()
    {
        Assert::assertTrue(Password::verify('test', self::FIXED_SSHA));
    }

    /**
     * Test hardcoded/known ssha hash
     */
    public function testSshaPregeneratedFalse()
    {
        Assert::assertFalse(Password::verify('testx', self::FIXED_SSHA));
    }

    /**
     * Test generated ssha hash
     */
    public function testSshaGeneratedTrue()
    {
        $hash = Password::hash('test', PASSWORD_SSHA);
        Assert::assertTrue(Password::verify('test', $hash));
    }

    /**
     * Test getInfo metadata for bcrypt
     */
    public function testGetInfoBcrypt()
    {
        $info = Password::getInfo(self::FIXED_BCRYPT);
        Assert::assertArrayHasKey('algoName', $info);
        Assert::assertEquals('bcrypt', $info['algoName'], 'Returned algoName should be "bcrypt"');
    }

    /**
     * Test getInfo metadata for ssha
     */
    public function testGetInfoSsha()
    {
        $info = Password::getInfo(self::FIXED_SSHA);
        Assert::assertArrayHasKey('algoName', $info);
        Assert::assertEquals('ssha', $info['algoName'], 'Returned algoName should be "ssha"');
    }

    /**
     * Test getInfo metadata for unknown
     */
    public function testGetInfoUnknown()
    {
        $info = Password::getInfo('loremipsum');
        Assert::assertArrayHasKey('algoName', $info);
        Assert::assertEquals('unknown', $info['algoName'], 'Returned algoName should be "unknown"');
    }

    /**
     * Test rehash false
     */
    public function testNeedsRehashFalse()
    {
        $rehash = Password::needsRehash(self::FIXED_BCRYPT, PASSWORD_BCRYPT);
        Assert::assertFalse($rehash, 'Fixed bcrypt should not need to be rehashed');

        $rehash = Password::needsRehash(self::FIXED_SSHA, PASSWORD_SSHA);
        Assert::assertFalse($rehash, 'Fixed ssha should not need to be rehashed');

        $rehash = Password::needsRehash(self::FIXED_BCRYPT, PASSWORD_BCRYPT, ['cost' => 10]);
        Assert::assertFalse($rehash, 'Fixed bcrypt (cost 10) should not need to be rehashed');
    }

    /**
     * Test rehash true
     */
    public function testNeedsRehashTrue()
    {
        $rehash = Password::needsRehash(self::FIXED_BCRYPT, PASSWORD_SSHA);
        Assert::assertTrue($rehash, 'Fixed bcrypt needs rehash');

        $rehash = Password::needsRehash(self::FIXED_SSHA, PASSWORD_BCRYPT);
        Assert::assertTrue($rehash, 'Fixed ssha needs rehash');

        $rehash = Password::needsRehash(self::FIXED_BCRYPT, PASSWORD_BCRYPT, ['cost' => 14]);
        Assert::assertTrue($rehash, 'Fixed bcrypt (cost 14) needs rehash');
    }
}
