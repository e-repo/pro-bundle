<?php

declare(strict_types=1);

namespace Blog\Application\Post\Post\Command\Create;

use Blog\Application\Common\FileStorage\MimeTypeInterface;
use Blog\Application\Common\FileStorage\SystemFileType;
use Blog\Domain\Post\Entity\Dto\ImageDto;
use Blog\Domain\Post\Entity\Dto\MetadataDto;
use Blog\Domain\Post\Entity\Dto\PostDto;
use Blog\Domain\Post\Entity\ImageType;
use Blog\Domain\Post\Entity\Post;
use Blog\Domain\Post\Entity\Specification\Post\SpecificationAggregator;
use Blog\Domain\Post\Entity\Status;
use Blog\Domain\Post\Repository\CategoryRepositoryInterface;
use Blog\Domain\Post\Repository\PostRepositoryInterface;
use CoreKit\Application\Bus\CommandHandlerInterface;
use CoreKit\Domain\Entity\FileMetadata;
use CoreKit\Domain\Entity\Id;
use CoreKit\Domain\Repository\FileMetadataRepositoryInterface;
use CoreKit\Domain\Service\FileStorage\FileUploadException;
use CoreKit\Domain\Service\FileStorage\StorageService;
use CoreKit\Domain\Service\FileStorage\Upload;
use DateTimeImmutable;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private SpecificationAggregator $specificationAggregator,
        private FileMetadataRepositoryInterface $fileMetadataRepository,
        private StorageService $storageService,
        private PostRepositoryInterface $postRepository,
        private SluggerInterface $slugger,
        private LoggerInterface $logger,
        private MimeTypeInterface $mimeType,
    ) {}

    public function __invoke(Command $command): void
    {
        $category = $this->categoryRepository
            ->findById(
                $command->categoryId
            );

        if (null === $category) {
            throw new DomainException('Категория не найдена.');
        }

        $fileKey = Id::next();

        $this->sendImageToStorage($command->image, $fileKey);

        $post = new Post(
            postDto: $this->makePost($command, $fileKey),
            category: $category,
            specificationAggregator: $this->specificationAggregator,
        );

        $this->postRepository->add($post);
    }

    private function makePost(Command $command, Id $fileKey): PostDto
    {
        $slug = $this->slugger
            ->slug(
                $command->shortTitle
            )
            ->toString();

        $meta = null;

        if (null !== $command->meta) {
            $meta = $this->makeMeta(
                $command->meta
            );
        }

        return new PostDto(
            slug: strtolower($slug),
            title: $command->title,
            shortTitle: $command->shortTitle,
            content: $command->content,
            status: Status::DRAFT,
            image: $this->makeImage($fileKey),
            meta: $meta,
        );
    }

    private function makeImage(Id $fileKey): ImageDto
    {
        return new ImageDto(
            fileKey: $fileKey,
            type: ImageType::MAIN,
            createdAt: new DateTimeImmutable(),
        );
    }

    private function makeMeta(Command\MetaCommand $meta): MetadataDto
    {
        return new MetadataDto(
            keyword: $meta->keyword,
            description: $meta->description,
        );
    }

    private function sendImageToStorage(
        Command\ImageCommand $image,
        Id $fileKey
    ): void {
        $upload = new Upload(
            name: $image->originalFileName,
            key: $fileKey->value,
            systemFileType: SystemFileType::POST_IMAGE->value,
            extension: $image->extension,
            file: $image->file
        );

        $this->addFileMetadata($image, $fileKey);

        try {
            $this->storageService->upload($upload);

            unlink($upload->file->getRealPath());
        } catch (FileUploadException $exception) {
            $this->logger->error('Ошибка загрузки изображения в хранилище', [
                'errorMessage' => $exception->getMessage(),
                'fileName' => $image->originalFileName,
            ]);

            throw new DomainException(
                'Не удалось загрузить изображение в хранилище. Попробуйте позднее или свяжитесь с администратором.'
            );
        }
    }

    private function addFileMetadata(
        Command\ImageCommand $image,
        Id $fileKey
    ): void {
        $type = $this->mimeType->guessMimeType($image->file->getRealPath());

        $fileMetadata = new FileMetadata(
            key: $fileKey->value,
            name: $image->originalFileName,
            type: $type,
            extension: $image->extension,
            createdAt: new DateTimeImmutable(),
        );

        $this->fileMetadataRepository->add($fileMetadata);
    }
}
