<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

use Guuzen\ResourceComposer\Config\JoinPass;
use Guuzen\ResourceComposer\Config\Link;

final class ResourceComposer
{
    /**
     * @var array<int, Link>
     */
    private array $links = [];

    /**
     * @var array<string, ResourceLoader>
     */
    private array $loadResources = [];

    public function registerMainResource(MainResource $mainResource): void
    {
        $this->links = [...$this->links, ...$mainResource->getConfigs()];
    }

    public function registerRelatedResource(RelatedResource $relatedResource): void
    {
        $this->loadResources[$relatedResource->getResource()] = $relatedResource->getLoader();
    }

    /**
     * @param array<int, array> $resources
     *
     * @return array<int, array>
     */
    public function composeList(array $resources, string $resourceType): array
    {
        $result    = self::denormalize($resources);
        $processes = $this->nextPasses([$resourceType]);

        if ($processes === []) {
            return $resources;
        }

        $this->run($processes, [$resourceType => $result]);

        return self::normalize($result);
    }

    public function compose(array $resource, string $resourceType): array
    {
        return $this->composeList([$resource], $resourceType)[0];
    }

    /**
     * @param array<int, JoinPass>                    $joinPasses
     * @param array<string, array<int, \ArrayObject>> $resourceGroups
     */
    private function run(array $joinPasses, array $resourceGroups): void
    {
        $relatedLoadedResources = [];
        $loadedResourcesGroups  = [];
        foreach ($joinPasses as $joinPass) {
            $loadIds = [];
            foreach ($joinPass->links as $mainResource => $links) {
                $resources = $resourceGroups[$mainResource];
                foreach ($links as $link) {
                    foreach ($resources as $resource) {
                        $loadIds = [...$loadIds, ...$link->join->loadIds($resource)];
                    }
                }
            }

            $loadResources   = $this->loadResources[$joinPass->relatedResource];
            $loadedResources = self::denormalize(
                $loadResources->load(
                    array_filter(array_unique($loadIds))
                )
            );

            $relatedLoadedResources[]                          = $joinPass->relatedResource;
            $loadedResourcesGroups[$joinPass->relatedResource] = $loadedResources;

            foreach ($joinPass->links as $mainResource => $links) {
                $resources = $resourceGroups[$mainResource];
                foreach ($links as $link) {
                    $groups = $link->group->group($loadedResources);
                    foreach ($resources as $resource) {
                        $link->join->resolve($resource, $groups);
                    }
                }
            }
        }

        $nextPasses = $this->nextPasses($relatedLoadedResources);
        if ($nextPasses === []) {
            return;
        }

        $this->run($nextPasses, $loadedResourcesGroups);
    }

    /**
     * @param array<int, string> $mainResources
     *
     * @return array<int, JoinPass>
     */
    private function nextPasses(array $mainResources): array
    {
        $passes = [];

        $relatedResources = [];
        foreach ($this->links as $link) {
            if (in_array($link->mainResource, $mainResources, true) === true) {
                $relatedResources[] = $link->relatedResource;
            }
        }
        $relatedResources = array_unique($relatedResources);

        foreach ($relatedResources as $relatedResource) {
            $pass = new JoinPass($relatedResource);
            foreach ($this->links as $link) {
                $pass->addLink($link);
            }
            $passes[] = $pass;
        }

        return $passes;
    }

    /**
     * @param array<int, array> $resources
     *
     * @return array<int, \ArrayObject>
     */
    public static function denormalize(array $resources): array
    {
        $arrayObjects = [];
        foreach ($resources as $key => $resource) {
            $arrayObjects[$key] = new \ArrayObject($resource);
        }

        return $arrayObjects;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return array<int, array>
     */
    public static function normalize(iterable $iterable): array
    {
        $result = [];
        /**
         * @psalm-var mixed $item
         * @var array-key   $key
         */
        foreach ($iterable as $key => $item) {
            if (\is_iterable($item) === true) {
                $result[$key] = self::normalize($item);
            } else {
                /** @psalm-suppress MixedAssignment */
                $result[$key] = $item;
            }
        }

        return $result;
    }
}
