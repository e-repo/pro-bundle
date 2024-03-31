<?php

declare(strict_types=1);

namespace Test\Functional;

use Test\Common\FunctionalTestCase;
use UI\Http\Common\DataFromJsonResponseTrait;

final class OpenApiDocumentationTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    public function testAvailableDocumentation(): void
    {
        // arrange
        $client = $this->createClient();

        // action
        $client->jsonRequest(
            method: 'GET',
            uri: '/api/doc.json'
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsSuccessful();
        self::assertArrayHasKey('openapi', $response);
        self::assertArrayHasKey('info', $response);
        self::assertArrayHasKey('paths', $response);
        self::assertArrayNotHasKey('message', $response);
    }
}
