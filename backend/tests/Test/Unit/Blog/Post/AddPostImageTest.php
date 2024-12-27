<?php

declare(strict_types=1);

namespace Test\Unit\Blog\Post;

use Blog\Domain\Post\Entity\Category;
use Blog\Domain\Post\Entity\Dto\ImageDto;
use Blog\Domain\Post\Entity\Dto\PostDto;
use Blog\Domain\Post\Entity\Event\PostCreatedEvent;
use Blog\Domain\Post\Entity\ImageType;
use Blog\Domain\Post\Entity\Post;
use Blog\Domain\Post\Entity\Specification\Post\SpecificationAggregator;
use Blog\Domain\Post\Entity\Specification\Post\UniqueShortTitle;
use Blog\Domain\Post\Entity\Specification\Post\UniqueTitle;
use Blog\Domain\Post\Entity\Status;
use CoreKit\Domain\Entity\Id;
use DomainException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AddPostImageTest extends KernelTestCase
{
    public Category $categoryMock;

    public SpecificationAggregator $specificationAggregator;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoryMock = $this->createMock(Category::class);
        $this->categoryMock
            ->method('getId')
            ->willReturn(Id::next());

        $this->categoryMock
            ->method('getName')
            ->willReturn('Комнатные растения.');

        $uniqueShortTitleSpecification = $this->createStub(UniqueShortTitle::class);
        $uniqueShortTitleSpecification
            ->method('isSatisfiedBy')
            ->willReturn(true);

        $uniqueTitleSpecification = $this->createStub(UniqueTitle::class);
        $uniqueTitleSpecification
            ->method('isSatisfiedBy')
            ->willReturn(true);

        self::getContainer()->set(UniqueShortTitle::class, $uniqueShortTitleSpecification);
        self::getContainer()->set(UniqueTitle::class, $uniqueTitleSpecification);

        $this->specificationAggregator = self::getContainer()->get(SpecificationAggregator::class);
    }

    public function testSuccess(): void
    {
        // arrange
        $postId = Uuid::uuid4()->toString();
        $imageId = Uuid::uuid4()->toString();

        $postDto = new PostDto(
            slug: 'bonsay',
            title: 'Бонсай',
            shortTitle: 'Бонсай',
            content: 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            status: Status::DRAFT,
            image: new ImageDto(new Id($imageId), ImageType::MAIN),
            id: $postId,
        );

        // action
        $post = new Post(
            postDto: $postDto, // добавляет изображение в момент создания
            category: $this->categoryMock,
            specificationAggregator: $this->specificationAggregator,
        );

        // assert
        self::assertCount(1, $post->getImages());
        self::assertCount(1, $post->getRecordedEvents());
        self::assertEquals($imageId, $post->getImages()->first()->getFileKey()->value);
        self::assertEquals($postId, $post->getId()->value);
        self::assertEquals($postDto->slug, $post->getSlug());
        self::assertEquals($postDto->title, $post->getTitle());
        self::assertEquals($postDto->shortTitle, $post->getShortTitle());
        self::assertEquals($postDto->content, $post->getContent());
        self::assertEquals($postDto->status, $post->getStatus());

        /** @var PostCreatedEvent $postCreatedEvent */
        $postCreatedEvent = $post->getRecordedEvents()[0];
        self::assertEquals('Комнатные растения.', $postCreatedEvent->categoryName);
    }

    public function testFailedByImageTypeAlreadyExists(): void
    {
        // arrange
        $postId = Uuid::uuid4()->toString();
        $imageId = Uuid::uuid4()->toString();

        $postDto = new PostDto(
            slug: 'bonsay',
            title: 'Бонсай',
            shortTitle: 'Бонсай',
            content: 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            status: Status::DRAFT,
            image: new ImageDto(new Id($imageId), ImageType::MAIN),
            id: $postId,
        );

        // action
        $post = new Post(
            postDto: $postDto,
            category: $this->categoryMock,
            specificationAggregator: $this->specificationAggregator,
        );

        $exceptionMessage = null;

        try {
            $post->addImage(
                new ImageDto(new Id($imageId), ImageType::MAIN)
            );
        } catch (DomainException $exception) {
            $exceptionMessage = $exception->getMessage();
        }

        // assert
        self::assertNotNull($exceptionMessage);
        self::assertMatchesRegularExpression(
            "/Изображение c типом '.+?' у поста '.+?' уже существует\./",
            $exceptionMessage
        );
        self::assertCount(1, $post->getImages());
    }

    public function testFailedByImageAlreadyExists(): void
    {
        // arrange
        $postId = Uuid::uuid4()->toString();
        $imageId = Uuid::uuid4()->toString();

        $postDto = new PostDto(
            slug: 'bonsay',
            title: 'Бонсай',
            shortTitle: 'Бонсай',
            content: 'Бонсай - искусство выращивания точной копии настоящего дерева в миниатюре',
            status: Status::DRAFT,
            image: new ImageDto(new Id($imageId), ImageType::MAIN),
            id: $postId,
        );

        // action
        $post = new Post(
            postDto: $postDto,
            category: $this->categoryMock,
            specificationAggregator: $this->specificationAggregator,
        );

        $exceptionMessage = null;

        try {
            $post->addImage(
                new ImageDto(new Id($imageId), ImageType::MAIN_THUMBNAIL_400)
            );
        } catch (DomainException $exception) {
            $exceptionMessage = $exception->getMessage();
        }

        // assert
        self::assertNotNull($exceptionMessage);
        self::assertMatchesRegularExpression(
            "/Данное изображение уже было добавлено, fileKey - .+/",
            $exceptionMessage
        );
        self::assertCount(1, $post->getImages());
    }
}
