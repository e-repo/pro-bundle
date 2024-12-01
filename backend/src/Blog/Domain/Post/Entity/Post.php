<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity;

use Blog\Domain\Post\Entity\Dto\PostDto;
use Blog\Infra\Post\Repository\PostRepository;
use CoreKit\Domain\Entity\Id;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(schema: 'blog')]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', options: [
        'comment' => 'Код поста',
    ])]
    private Id $id;

    #[ORM\Column(length: 255, options: [
        'comment' => 'slug поста',
    ])]
    private string $slug;

    #[ORM\Column(length: 255, options: [
        'comment' => 'Заголовок',
    ])]
    private string $title;

    #[ORM\Column(length: 100, options: [
        'comment' => 'Сокращенный заголовок, для карточки поста',
    ])]
    private string $shortTitle;

    #[ORM\Column(type: Types::TEXT, options: [
        'comment' => 'Содержание статьи',
    ])]
    private string $content;

    #[ORM\Column(length: 100, enumType: Status::class, options: [
        'comment' => 'статус поста',
    ])]
    private Status $status;

    #[ORM\Column(options: [
        'comment' => 'Доступность комментариев',
        'default' => false,
    ])]
    private bool $commentAvailable = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, options: [
        'comment' => 'Категория поста',
    ])]
    private Category $category;

    #[ORM\Embedded(Metadata::class)]
    private ?Metadata $meta;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    public function __construct(
        PostDto $postDto
    ) {
        $this->id = null === $postDto->id
            ? Id::next()
            : new Id($postDto->id);

        $this->slug = $postDto->slug;
        $this->title = $postDto->title;
        $this->shortTitle = $postDto->shortTitle;
        $this->content = $postDto->content;
        $this->status = $postDto->status;
        $this->category = $postDto->category;
        $this->createdAt = new DateTimeImmutable();

        if (null !== $postDto->meta) {
            $this->meta = new Metadata(
                keyword: $postDto->meta->keyword,
                description: $postDto->meta->description,
            );
        }
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getShortTitle(): string
    {
        return $this->shortTitle;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getMeta(): ?Metadata
    {
        return $this->meta;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
