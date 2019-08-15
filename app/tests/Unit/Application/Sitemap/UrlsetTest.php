<?php

declare(strict_types=1);

namespace Musement\Tests\Unit\Application\Sitemap;

use Musement\Application\Exception\RuntimeException;
use Musement\Application\Sitemap\Urlset;
use PHPUnit\Framework\TestCase;

class UrlsetTest extends TestCase
{
    public function test_cant_create_empty_urlset(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Urlset cannot be empty.');

        new Urlset('');
    }
}
