<?php

declare(strict_types=1);

namespace Test\Functional\Common\Fixture\Blog;

use Blog\Domain\Reader\Entity\NameVo;
use Blog\Domain\Reader\Entity\Reader;
use Blog\Domain\Reader\Entity\Specification\SpecificationAggregator;
use CoreKit\Domain\Entity\Email;
use CoreKit\Domain\Entity\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Common\Fixture\BaseFixtureTrait;
use Test\Functional\Common\Fixture\ReferencableInterface;

class BaseReaderFixture extends Fixture implements ReferencableInterface
{
    use BaseFixtureTrait;

    public function __construct(
        private readonly SpecificationAggregator $specificationAggregator,
    ) {}

    public function load(ObjectManager $manager): void
    {
        foreach (static::allItems() as $key => $item) {
            ++$key;

            $reader = new Reader(
                id: new Id($item['id']),
                name: new NameVo(
                    first: $item['firstName'],
                    last: $item['lastName'],
                ),
                email: new Email($item['email']),
                specificationAggregator: $this->specificationAggregator
            );

            $manager->persist($reader);

            $this->addReference(self::getReferenceName($key), $reader);
        }

        $manager->flush();
    }

    public static function getPrefix(): string
    {
        return 'reader';
    }
}
