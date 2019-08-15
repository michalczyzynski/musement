<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\Sitemap\PixelDeveloper;

use Musement\Application\Sitemap\Urlset;
use Musement\Application\Sitemap\Urlset\Url;
use Musement\Application\Sitemap\Urlset\UrlsetFactoryInterface;
use Thepixeldeveloper\Sitemap\Drivers\XmlWriterDriver;
use Thepixeldeveloper\Sitemap\Url as VendorUrl;
use Thepixeldeveloper\Sitemap\Urlset as VendorUrlset;

class PixelDeveloperUrlsetFactory implements UrlsetFactoryInterface
{
    public function create(Url ...$urls): Urlset
    {
        $vendorUrlset = new VendorUrlset();

        \array_map(
            static function (Url $url) use ($vendorUrlset) {
                $vendorUrl = new VendorUrl($url->url());
                $vendorUrl->setPriority((string) $url->priority());

                $vendorUrlset->add($vendorUrl);
            },
            $urls
        );

        $driver = new XmlWriterDriver();
        $vendorUrlset->accept($driver);

        return new Urlset($driver->output());
    }
}
