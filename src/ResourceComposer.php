<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

final class ResourceComposer
{
    /**
     * @param array<class-string, array<class-string<ResourceLink>, ResourceLink>> $resourceLinks
     * @param iterable<ResourceLink>                                               $links
     * @param array<class-string<ResourceLoader>, ResourceLoader>                  $loaders
     */
    public function __construct(private array $resourceLinks, private iterable $links, private array $loaders)
    {
    }

    /**
     * @param array<int, object> $resources
     */
    public function loadRelated(array $resources): void
    {
        $resolvers = [];
        foreach ($this->links as $link) {
            $resolvers[$link::class] = $link->resolver();
        }

        $extractedIds = [];
        foreach ($resources as $resource) {
            $resourcelinks = $this->resourceLinks[$resource::class] ?? [];
            foreach ($resourcelinks as $resourcelink) {
                $resolver = $resolvers[$resourcelink::class];
                foreach ($resolver->extractIds($resource) as $extractedId) {
                    if ($extractedId === null) {
                        continue;
                    }
                    $extractedIds[$resource::class][$resourcelink::class][] = $extractedId;
                }
            }
        }

        $nextPassResources = [];
        $loadedResources = [];
        foreach ($extractedIds as $resourceClass => $resourceExtractedIds) {
            foreach ($resourceExtractedIds as $linkClass => $linkExtractedIds) {
                $resourceResolver = $this->resourceLinks[$resourceClass][$linkClass];
                $resourceLoader = $this->loaders[$resourceResolver->loaderClass()];
                $resolver = $resolvers[$linkClass];
                $loadedResources[$resourceClass][$linkClass] = $resolver->load($linkExtractedIds, $resourceLoader);
                $nextPassResources = [...$nextPassResources, ...$loadedResources[$resourceClass][$linkClass]];
            }
        }

        foreach ($resources as $resource) {
            $link = $this->resourceLinks[$resource::class] ?? [];
            foreach ($link as $resourceResolver) {
                $resolver = $resolvers[$resourceResolver::class];
                $resolver->group($loadedResources[$resource::class][$resourceResolver::class]);
                $resolver->resolve($resource);
            }
        }

        if ($nextPassResources !== []) {
            $this->loadRelated($nextPassResources);
        }
    }

    /**
     * @param iterable<ResourceLink>   $links
     * @param iterable<ResourceLoader> $loaders
     */
    public static function create(iterable $links, iterable $loaders): self
    {
        $initLinks = [];
        foreach ($links as $link) {
            $initLinks[$link->resourceClass()][$link::class] = $link;
        }

        $initLoaders = [];
        foreach ($loaders as $loader) {
            $initLoaders[$loader::class] = $loader;
        }

        return new self($initLinks, $links, $initLoaders);
    }
}
