<?php

declare(strict_types=1);

namespace Blog\Application\Reader\Command\CreateOrUpdate;

use Blog\Domain\Reader\Entity\NameVo;
use Blog\Domain\Reader\Entity\Reader;
use Blog\Domain\Reader\Entity\ReaderHashVo;
use Blog\Domain\Reader\Entity\Specification\SpecificationAggregator;
use Blog\Domain\Reader\Repository\ReaderRepositoryInterface;
use CoreKit\Application\Bus\CommandHandlerInterface;
use CoreKit\Domain\Entity\Email;
use CoreKit\Domain\Entity\Id;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private ReaderRepositoryInterface $readerRepository,
        private SpecificationAggregator $specificationAggregator,
    ) {}

    public function __invoke(Command $command): void
    {
        $reader = $this->readerRepository->findById($command->id);

        if (null !== $reader) {
            $reader->update(
                new ReaderHashVo(
                    firstname: $command->firstname,
                    lastname: $command->lastname,
                    email: $command->email,
                )
            );

            return;
        }

        $reader = new Reader(
            id: new Id($command->id),
            name: new NameVo(
                first: $command->firstname,
                last: $command->lastname,
            ),
            email: new Email($command->email),
            specificationAggregator: $this->specificationAggregator,
        );

        $this->readerRepository->add($reader);
    }
}
