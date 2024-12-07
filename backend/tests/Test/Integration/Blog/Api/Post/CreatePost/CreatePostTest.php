<?php

declare(strict_types=1);

namespace Test\Integration\Blog\Api\Post\CreatePost;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Test\Common\DataFromJsonResponseTrait;
use Test\Common\FunctionalTestCase;
use Test\Integration\Common\User\UserBuilder;

final class CreatePostTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    private const ENDPOINT_URL = '/api/blog/v1/post';

    public function testSuccessCreatePost(): void
    {
        // arrange
        $client = $this->createClient();
        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        $file = new UploadedFile(
            path: __DIR__ . '/Data/img.png',
            originalName: 'img.png',
            test: true,
        );

        $expectedPost = [
            'title' => 'Бонсай',
            'shortTitle' => 'Бонсай',
            'content' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            'categoryUuid' => 'f84c29cf-5f37-47d5-a790-8ca8008bbdf8',
            'metaKeyword' => 'бонсай, дерево в миниатюре',
            'metaDescription' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
        ];

        // action
        $client->request(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'payload' => "{
                   \"title\":\"{$expectedPost['title']}\",
                   \"shortTitle\":\"{$expectedPost['shortTitle']}\",
                   \"content\":\"{$expectedPost['content']}\",
                   \"categoryUuid\":\"{$expectedPost['categoryUuid']}\",
                   \"metaKeyword\":\"{$expectedPost['metaKeyword']}\",
                   \"metaDescription\":\"{$expectedPost['metaDescription']}\"
                }",
            ],
            files: [
                'file' => $file,
            ]
        );
    }
}
