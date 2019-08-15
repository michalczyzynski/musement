<?php

declare(strict_types=1);

namespace Musement\Tests\Integration\UserInterface\CLI\Command;

use Musement\Tests\Double\Mock\Http\HttpClientMock;
use Musement\Tests\Integration\UserInterface\CLI\CommandTestCase;
use Musement\UserInterface\CLI\Command\SendSitemapOverEmail;
use Nyholm\Psr7\Response;

class SendSitemapOverEmailTest extends CommandTestCase
{
    public function test_builds_sitemap(): void
    {
        /** @var \Swift_Mailer $mailer */
        $mailer = $this->get('mailer');
        $logger = new \Swift_Plugins_MessageLogger();
        $mailer->registerPlugin($logger);

        /** @var HttpClientMock $httpClientMock */
        $httpClientMock = $this->get('musement.sdk.musement_catalog.http.client');
        $httpClientMock
            ->expects(
                'GET', 'https://api.musement.com/api/v3/cities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/cities.json'))
            )
            ->expects(
                'GET', 'https://api.musement.com/api/v3/cities/57/activities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/activities.json'))
            )
            ->expects(
                'GET', 'https://api.musement.com/api/v3/cities/40/activities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/activities.json'))
            )
            ->expects(
                'GET', 'https://api.musement.com/api/v3/cities/2/activities?limit=2&offset=0',
                new Response(200, [], \file_get_contents(__DIR__ . '/../../../../../../lib/sdk/musement_catalog/tests/Resource/v3/activities.json'))
            );

        $result = $this->executeCommand(SendSitemapOverEmail::NAME, [
            'locale' => 'it',
            'recipients' => [
                'test-1@musement.com',
                'test-2@musement.com',
            ],
        ]);

        self::assertEquals(0, $result->getStatusCode());
        self::assertEquals(1, $logger->countMessages());
        self::assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../../../Resource/sitemap.xml',
            $logger->getMessages()[0]->getChildren()[0]->getBody()
        );
    }
}
