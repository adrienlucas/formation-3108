<?php

declare(strict_types=1);

namespace App\Gateway;


use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CacheableNasaGateway extends NasaGateway
{
    public const CACHE_KEY = 'nasa_gateway_cached_response';

    /** @var NasaGateway */
    private $actualGateway;

    private $cache;

    public function __construct(NasaGateway $actualGateway, CacheInterface $cache)
    {
        $this->actualGateway = $actualGateway;
        $this->cache = $cache;
    }

    public function isEarthInDanger(): bool
    {
        return $this->cache->get(self::CACHE_KEY, function(ItemInterface $cacheItem){
            $cacheItem->expiresAfter(10);
            return $this->actualGateway->isEarthInDanger();
        });
    }

}
