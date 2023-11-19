<?php

declare(strict_types=1);

namespace Common\Application\Subscriber;

use Common\Application\Bus\EventBusInterface;
use Common\Domain\Entity\HasEventsInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class DomainEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
    ) {
    }


    public static function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $this->sendEntityDomainEvents($args);
    }

    public function sendEntityDomainEvents(OnFlushEventArgs $args): void
    {
        $uow = $args->getObjectManager()->getUnitOfWork();

        $sources = [
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions(),
        ];

        foreach ($sources as $source) {
            foreach ($source as $entity) {
                if (false === $entity instanceof HasEventsInterface) {
                    continue;
                }

                $this->sendRecordedEvents($entity);
            }
        }

        // ToDo: проверить бага двойной отправки
        $collectionSources = [
            $uow->getScheduledCollectionDeletions(),
            $uow->getScheduledCollectionUpdates(),
        ];

        foreach ($collectionSources as $source) {
            /** @var PersistentCollection $collection */
            foreach ($source as $collection) {
                $entity = $collection->getOwner();

                if (false === $entity instanceof HasEventsInterface) {
                    continue;
                }

                $this->sendRecordedEvents($entity);
            }
        }
    }

    private function sendRecordedEvents(HasEventsInterface $entity): void
    {
        foreach ($entity->getRecordedEvents() as $event) {
            $this->eventBus->publish($event);
        }

        $entity->clearRecordedEvents();
    }
}
