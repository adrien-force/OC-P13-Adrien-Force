<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProductCacheInvalidationListener
{
    private TagAwareCacheInterface $cache;

    public function __construct(TagAwareCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Product) {
            $this->invalidateProductCache();
        }
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Product) {
            $this->invalidateProductCache();
        }
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Product) {
            $this->invalidateProductCache();
        }
    }

    private function invalidateProductCache(): void
    {
        $this->cache->invalidateTags([Product::CACHE_TAG_LIST]);
    }
}
