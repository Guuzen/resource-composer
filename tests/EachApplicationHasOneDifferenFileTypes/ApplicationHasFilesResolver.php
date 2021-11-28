<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use Guuzen\ResourceComposer\OneToOne;
use Guuzen\ResourceComposer\ResourceResolver;

/**
 * @implements ResourceResolver<Application, File>
 */
final class ApplicationHasFilesResolver implements ResourceResolver
{
    /**
     * @param array<int, File> $files
     */
    public function __construct(private array $files, private OneToOne $oneToOne)
    {
    }

    public function extractIds(object $resource): \Traversable
    {
        yield $resource->fileAId;
        yield $resource->fileBId;
    }

    public function load(array $ids): array
    {
        return $this->files;
    }

    public function resolve(object $resource, array $loadedResources): void
    {
        $grouped = $this->oneToOne->group($loadedResources, fn (File $file) => $file->id);
        $resource->fileA = $grouped[$resource->fileAId];
        $resource->fileB = $grouped[$resource->fileBId];
    }

    public function resourceClass(): string
    {
        return Application::class;
    }
}
