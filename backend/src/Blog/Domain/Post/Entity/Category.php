<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity;

use Blog\Infra\Post\Repository\CategoryRepository;
use CoreKit\Domain\Entity\Id;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(schema: 'blog')]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', options: [
        'comment' => 'Код категории',
    ])]
    private Id $id;

    #[ORM\Column(length: 50, options: [
        'comment' => 'Наименование категории',
    ])]
    private string $name;

    #[ORM\Column(length: 255, options: [
        'comment' => 'Описание категории',
    ])]
    private string $description;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: [
        'comment' => 'Дата создания категории',
    ])]
    private DateTimeImmutable $createdAt;

    public function __construct(
        Id $id,
        string $name,
        string $description,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
