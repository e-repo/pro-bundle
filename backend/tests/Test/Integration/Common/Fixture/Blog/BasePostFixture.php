<?php

declare(strict_types=1);

namespace Test\Integration\Common\Fixture\Blog;

use Blog\Domain\Post\Entity\Dto\ImageDto;
use Blog\Domain\Post\Entity\Dto\MetadataDto;
use Blog\Domain\Post\Entity\Dto\PostDto;
use Blog\Domain\Post\Entity\ImageType;
use Blog\Domain\Post\Entity\Post;
use Blog\Domain\Post\Entity\Specification\Post\SpecificationAggregator;
use Blog\Domain\Post\Entity\Status;
use CoreKit\Domain\Entity\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Test\Integration\Blog\Api\Post\CreateCategory\CategoryFixture;
use Test\Integration\Common\Fixture\BaseFixtureTrait;
use Test\Integration\Common\Fixture\ReferencableInterface;

class BasePostFixture extends Fixture implements ReferencableInterface
{
    use BaseFixtureTrait;

    public function __construct(
        private readonly SpecificationAggregator $specificationAggregator,
    ) {}

    public function load(ObjectManager $manager): void
    {
        foreach (static::allItems() as $key => $item) {
            $key++;

            $post = new Post(
                postDto: new PostDto(
                    slug: $item['slug'],
                    title: $item['title'],
                    shortTitle: $item['shortTitle'],
                    content: $item['content'],
                    status: Status::from($item['status']),
                    image: new ImageDto(
                        fileKey: new Id($item['fileKey']),
                        type: ImageType::from($item['imageType'])
                    ),
                    id: $item['id'],
                    commentAvailable: $item['commentAvailable'],
                    meta: new MetadataDto(
                        keyword: $item['metaKeyword'],
                        description: $item['metaDescription'],
                    )
                ),
                category: $this->getReference(
                    name: CategoryFixture::getReferenceName($key)
                ),
                specificationAggregator: $this->specificationAggregator,
            );

            $manager->persist($post);

            $this->addReference(self::getReferenceName($key), $post);
        }

        $manager->flush();
    }

    public static function getPrefix(): string
    {
        return 'post';
    }
}
