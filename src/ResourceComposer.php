<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

final class ResourceComposer
{
    /**
     * @param array<class-string, array<class-string<ResourceResolver>, ResourceResolver>> $resolvers
     */
    public function __construct(private array $resolvers)
    {
    }

    /**
     * @param array<int, object> $resources
     */
    public function loadRelated(array $resources): void
    {
        $extractedIds = [];
        foreach ($resources as $resource) {
            $resourceResolvers = $this->resolvers[$resource::class] ?? [];
            foreach ($resourceResolvers as $resourceResolver) {
                foreach ($resourceResolver->extractIds($resource) as $extractedId) {
                    if ($extractedId === null) {
                        continue;
                    }
                    $extractedIds[$resource::class][$resourceResolver::class][] = $extractedId;
                }
            }
        }

        $nextPassResources = [];
        $loadedResources = [];
        foreach ($extractedIds as $resourceClass => $resourceExtractedIds) {
            foreach ($resourceExtractedIds as $resolverClass => $resolverExtractedIds) {
                $resourceResolver = $this->resolvers[$resourceClass][$resolverClass];
                $loadedResources[$resourceClass][$resolverClass] = $resourceResolver->load($resolverExtractedIds);
                $nextPassResources = [...$nextPassResources, ...$loadedResources[$resourceClass][$resolverClass]];
            }
        }

        foreach ($resources as $resource) {
            $resourceResolvers = $this->resolvers[$resource::class] ?? [];
            foreach ($resourceResolvers as $resourceResolver) {
                $resourceResolver->resolve($resource, $loadedResources[$resource::class][$resourceResolver::class]);
            }
        }

        if ($nextPassResources !== []) {
            $this->loadRelated($nextPassResources);
        }
    }

    /**
     * @param iterable<ResourceResolver> $resolvers
     */
    public static function create(iterable $resolvers): self
    {
        $initResolvers = [];
        foreach ($resolvers as $resolver) {
            $initResolvers[$resolver->resourceClass()][$resolver::class] = $resolver;
        }

        return new self($initResolvers);
    }
}
