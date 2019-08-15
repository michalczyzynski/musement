<?php

declare(strict_types=1);

namespace Musement\Tests\Unit\Application\Sitemap\Urlset;

use Musement\Application\Exception\RuntimeException;
use Musement\Application\Sitemap\Urlset\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function test_throws_exception_when_creating_with_empty_url(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('"" is not a valid url.');

        new Url('', 1);
    }

    public function test_throws_exception_when_creating_with_invalid_url(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('"something something string" is not a valid url.');

        new Url('something something string', 1);
    }

    public function test_throws_exception_on_priority_too_low(): void
    {
        new Url($url = 'http://url.com', 0);

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Priority has to be between 0.0 and 1.0 inclusive.');

        new Url($url, -0.1);
    }

    public function test_throws_exception_on_priority_too_high(): void
    {
        new Url($url = 'http://url.com', 1);

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Priority has to be between 0.0 and 1.0 inclusive.');

        new Url($url, 1.1);
    }
}
