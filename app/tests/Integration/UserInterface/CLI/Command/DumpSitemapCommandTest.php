<?php

declare(strict_types=1);

namespace Musement\Tests\Integration\UserInterface\CLI\Command;

use Musement\Tests\Double\Mock\Http\HttpClientMock;
use Musement\Tests\Integration\UserInterface\CLI\CommandTestCase;
use Musement\UserInterface\CLI\Command\DumpSitemapCommand;
use Nyholm\Psr7\Response;

class DumpSitemapCommandTest extends CommandTestCase
{
    public function test_builds_sitemap(): void
    {
        /** @var HttpClientMock $httpClientMock */
        $httpClientMock = $this->get('musement.sdk.musement_catalog.http.client');
        $httpClientMock
            ->expects(
                'GET', 'https://this-is-api.com/api/v3/cities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/cities.json'))
            )
            ->expects(
                'GET', 'https://this-is-api.com/api/v3/cities/57/activities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/activities.json'))
            )
            ->expects(
                'GET', 'https://this-is-api.com/api/v3/cities/40/activities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/activities.json'))
            )
            ->expects(
                'GET', 'https://this-is-api.com/api/v3/cities/2/activities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/activities.json'))
            );

        $result = $this->executeCommand(DumpSitemapCommand::NAME, [
            'locale' => 'it',
        ]);

        self::assertEquals(0, $result->getStatusCode());
        self::assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../../../Resource/sitemap.xml',
            $result->getDisplay()
        );
    }
}
