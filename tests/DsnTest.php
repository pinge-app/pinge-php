<?php
declare(strict_types = 1);

namespace Pinge\Tests;

use Pinge\SDK\Dsn;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DsnTest extends TestCase
{
    public function test_parser_throws_on_seriously_malformed_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The DSN is invalid (http://user@:80).');

        Dsn::createFromString('http://user@:80');
    }

    public function test_parser_throws_on_missing_user(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The DSN (http://host.com/path) must contain a scheme, host, path and user.');

        Dsn::createFromString('http://host.com/path');
    }

    public function test_parser_throws_on_missing_scheme(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The DSN (user@host.com/path) must contain a scheme, host, path and user.');

        Dsn::createFromString('user@host.com/path');
    }

    public function test_parser_throws_on_missing_host(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The DSN (user@/path) must contain a scheme, host, path and user.');

        Dsn::createFromString('user@/path');
    }

    public function test_parser_throws_on_incorrect_scheme(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The scheme of the DSN (file://user@host.com/path) must be either http or https.');

        Dsn::createFromString('file://user@host.com/path');
    }

    public function test_parser_creates_a_dsn_object(): void
    {
        $dsn = Dsn::createFromString('http://token@host.com:81/1');

        $this->assertSame('http', $dsn->scheme());
        $this->assertSame('token', $dsn->token());
        $this->assertSame('host.com', $dsn->host());
        $this->assertSame(81, $dsn->port());
    }

    public function test_parser_reconstructs_dsn(): void
    {
        $dsn = Dsn::createFromString('http://token@host.com:81/');
        $this->assertSame('http://token@host.com:81/', (string) $dsn);
    }
}
