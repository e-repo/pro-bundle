<?php

declare(strict_types=1);

namespace Test\Integration\Blog\Api\Post\CreatePost;

use Blog\Domain\Post\Entity\Post;
use Blog\Domain\Post\Entity\PostImage;
use CoreKit\Domain\Entity\FileMetadata;
use CoreKit\Infra\FileStorage\S3StorageClient;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\MockObject\Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Test\Common\DataFromJsonResponseTrait;
use Test\Common\FunctionalTestCase;
use Test\Integration\Blog\Api\Post\GetOneCategory\CategoryFixture;
use Test\Integration\Common\User\UserBuilder;

final class CreatePostTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    private const ENDPOINT_URL = '/api/blog/v1/post';

    private ObjectRepository $postRepository;

    private ObjectRepository $postImageRepository;

    private ObjectRepository $fileMetadataRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool
            ->withPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE)
            ->loadFixtures([
                CategoryFixture::class,
                PostFixture::class,
            ]);

        $this->postRepository = $this->entityManager->getRepository(Post::class);
        $this->postImageRepository = $this->entityManager->getRepository(PostImage::class);
        $this->fileMetadataRepository = $this->entityManager->getRepository(FileMetadata::class);

        copy(
            __DIR__ . '/Data/img.png',
            __DIR__ . '/Data/img_temp.png'
        );
    }

    public function tearDown(): void
    {
        $pathToTempImage = __DIR__ . '/Data/img_temp.png';

        if (file_exists($pathToTempImage)) {
            unlink($pathToTempImage);
        }
    }

    public function testSuccessCreatePost(): void
    {
        // arrange
        $this->setMockS3StorageClient();

        $client = $this->createClient();
        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        $file = new UploadedFile(
            path: __DIR__ . '/Data/img_temp.png',
            originalName: 'img_temp.png',
            test: true,
        );

        $expectedPost = [
            'title' => 'Бонсай',
            'shortTitle' => 'Бонсай',
            'content' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            'categoryUuid' => '28912aa1-96ee-4631-8e34-14cd2f019e53',
            'metaKeyword' => 'бонсай, дерево в миниатюре',
            'metaDescription' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
        ];

        // action
        $client->request(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'payload' => '{
                   "title":"' . $expectedPost['title'] . '",
                   "shortTitle":"' . $expectedPost['shortTitle'] . '",
                   "content":"' . $expectedPost['content'] . '",
                   "categoryUuid":"' . $expectedPost['categoryUuid'] . '",
                   "metaKeyword":"' . $expectedPost['metaKeyword'] . '",
                   "metaDescription":"' . $expectedPost['metaDescription'] . '"
                }',
            ],
            files: [
                'file' => $file,
            ]
        );

        // assert
        /** @var Post $post */
        $post = $this->postRepository->findOneBy([
            'shortTitle' => $expectedPost['shortTitle'],
        ]);

        $fileKey = $post
            ->getImages()
            ->first()
            ->getFileKey();

        $fileMetadata = $this->fileMetadataRepository->find($fileKey);

        self::assertResponseIsSuccessful();
        self::assertEquals($post->getTitle(), $expectedPost['title']);
        self::assertEquals($post->getShortTitle(), $expectedPost['shortTitle']);
        self::assertEquals($post->getContent(), $expectedPost['content']);
        self::assertEquals($post->getCategory()->getId()->value, $expectedPost['categoryUuid']);
        self::assertEquals($post->getMeta()->keyword, $expectedPost['metaKeyword']);
        self::assertEquals($post->getMeta()->description, $expectedPost['metaDescription']);
        self::assertEquals($fileKey->value, $fileMetadata->getKey());
    }

    public function testFailedByCategoryNotFound(): void
    {
        // arrange
        $this->setMockS3StorageClient();

        $client = $this->createClient();
        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        $file = new UploadedFile(
            path: __DIR__ . '/Data/img_temp.png',
            originalName: 'img_temp.png',
            test: true,
        );

        $expectedPost = [
            'title' => 'Бонсай',
            'shortTitle' => 'Бонсай',
            'content' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            'categoryUuid' => '762169ba-8c49-4a7d-87cf-703d263e98ae',
            'metaKeyword' => 'бонсай, дерево в миниатюре',
            'metaDescription' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
        ];

        // action
        $client->request(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'payload' => '{
                   "title":"' . $expectedPost['title'] . '",
                   "shortTitle":"' . $expectedPost['shortTitle'] . '",
                   "content":"' . $expectedPost['content'] . '",
                   "categoryUuid":"' . $expectedPost['categoryUuid'] . '",
                   "metaKeyword":"' . $expectedPost['metaKeyword'] . '",
                   "metaDescription":"' . $expectedPost['metaDescription'] . '"
                }',
            ],
            files: [
                'file' => $file,
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals(
            'Категория не найдена.',
            $response['errors'][0]['detail']
        );
    }

    public function testFailedByNotAdmin(): void
    {
        // arrange
        $this->setMockS3StorageClient();

        $client = $this->createClient();

        $file = new UploadedFile(
            path: __DIR__ . '/Data/img_temp.png',
            originalName: 'img_temp.png',
            test: true,
        );

        $expectedPost = [
            'title' => 'Бонсай',
            'shortTitle' => 'Бонсай',
            'content' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            'categoryUuid' => '762169ba-8c49-4a7d-87cf-703d263e98ae',
            'metaKeyword' => 'бонсай, дерево в миниатюре',
            'metaDescription' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
        ];

        // action
        $client->request(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'payload' => '{
                   "title":"' . $expectedPost['title'] . '",
                   "shortTitle":"' . $expectedPost['shortTitle'] . '",
                   "content":"' . $expectedPost['content'] . '",
                   "categoryUuid":"' . $expectedPost['categoryUuid'] . '",
                   "metaKeyword":"' . $expectedPost['metaKeyword'] . '",
                   "metaDescription":"' . $expectedPost['metaDescription'] . '"
                }',
            ],
            files: [
                'file' => $file,
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        self::assertEquals(
            'Доступ запрещен.',
            $response['errors'][0]['detail']
        );
    }

    public function testFailedUploadFile(): void
    {
        // arrange
        $client = $this->createClient();
        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        $s3ClientStub = $this->createStub(S3StorageClient::class);
        $s3ClientStub
            ->method('upload')
            ->willReturnCallback(
                static fn () => throw new RuntimeException('Ошибка загрузки файла.')
            );

        $this->container->set(S3StorageClient::class, $s3ClientStub);

        $file = new UploadedFile(
            path: __DIR__ . '/Data/img_temp.png',
            originalName: 'img_temp.png',
            test: true,
        );

        $expectedPost = [
            'title' => 'Бонсай',
            'shortTitle' => 'Бонсай',
            'content' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            'categoryUuid' => '28912aa1-96ee-4631-8e34-14cd2f019e53',
            'metaKeyword' => 'бонсай, дерево в миниатюре',
            'metaDescription' => 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
        ];

        // action
        $client->request(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'payload' => '{
                   "title":"' . $expectedPost['title'] . '",
                   "shortTitle":"' . $expectedPost['shortTitle'] . '",
                   "content":"' . $expectedPost['content'] . '",
                   "categoryUuid":"' . $expectedPost['categoryUuid'] . '",
                   "metaKeyword":"' . $expectedPost['metaKeyword'] . '",
                   "metaDescription":"' . $expectedPost['metaDescription'] . '"
                }',
            ],
            files: [
                'file' => $file,
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals(
            'Не удалось загрузить изображение в хранилище. Попробуйте позднее или свяжитесь с администратором.',
            $response['errors'][0]['detail']
        );
    }

    public function testFailedByTitleAlreadyExists(): void
    {
        // arrange
        $this->setMockS3StorageClient();

        $client = $this->createClient();
        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        $file = new UploadedFile(
            path: __DIR__ . '/Data/img_temp.png',
            originalName: 'img_temp.png',
            test: true,
        );

        $expectedPost = [
            'title' => 'Суккуленты',
            'shortTitle' => 'Суккуленты 1',
            'content' => 'Суккуленты - растения, приспособленные для хранения воды в своих листьях или стеблях',
            'categoryUuid' => '28912aa1-96ee-4631-8e34-14cd2f019e53',
            'metaKeyword' => 'суккуленты, уход за суккулентами',
            'metaDescription' => 'Суккуленты - идеальные растения для любителей минимального ухода',
        ];

        // action
        $client->request(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'payload' => '{
                   "title":"' . $expectedPost['title'] . '",
                   "shortTitle":"' . $expectedPost['shortTitle'] . '",
                   "content":"' . $expectedPost['content'] . '",
                   "categoryUuid":"' . $expectedPost['categoryUuid'] . '",
                   "metaKeyword":"' . $expectedPost['metaKeyword'] . '",
                   "metaDescription":"' . $expectedPost['metaDescription'] . '"
                }',
            ],
            files: [
                'file' => $file,
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        $postCount = $this->postRepository->count([
            'title' => $expectedPost['title'],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals(
            'Данный заголовок статьи уже существует.',
            $response['errors'][0]['detail']
        );
        self::assertEquals(1, $postCount);
    }

    public function testFailedByShortTitleAlreadyExists(): void
    {
        // arrange
        $this->setMockS3StorageClient();

        $client = $this->createClient();
        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        $file = new UploadedFile(
            path: __DIR__ . '/Data/img_temp.png',
            originalName: 'img_temp.png',
            test: true,
        );

        $expectedPost = [
            'title' => 'Суккуленты 1',
            'shortTitle' => 'Суккуленты',
            'content' => 'Суккуленты - растения, приспособленные для хранения воды в своих листьях или стеблях',
            'categoryUuid' => '28912aa1-96ee-4631-8e34-14cd2f019e53',
            'metaKeyword' => 'суккуленты, уход за суккулентами',
            'metaDescription' => 'Суккуленты - идеальные растения для любителей минимального ухода',
        ];

        // action
        $client->request(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'payload' => '{
                   "title":"' . $expectedPost['title'] . '",
                   "shortTitle":"' . $expectedPost['shortTitle'] . '",
                   "content":"' . $expectedPost['content'] . '",
                   "categoryUuid":"' . $expectedPost['categoryUuid'] . '",
                   "metaKeyword":"' . $expectedPost['metaKeyword'] . '",
                   "metaDescription":"' . $expectedPost['metaDescription'] . '"
                }',
            ],
            files: [
                'file' => $file,
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        $postCount = $this->postRepository->count([
            'shortTitle' => $expectedPost['shortTitle'],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals(
            'Данный сокращенный заголовок статьи уже существует.',
            $response['errors'][0]['detail']
        );
        self::assertEquals(1, $postCount);
    }

    private function setMockS3StorageClient(): void
    {
        $s3ClientMock = $this->createMock(S3StorageClient::class);
        $this->container->set(S3StorageClient::class, $s3ClientMock);
    }
}
