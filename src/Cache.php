<?php

namespace ThirdPartyData\Decorator;

use DateTime;
use GuzzleHttp\Psr7\Response;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use ThirdPartyData\Integration\DataProviderInterface;

class Cache implements DataProviderInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    public $cache;

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var DataProviderInterface
     */
    private $provider;

    /**
     * @var null|string
     */
    private $prefix;

    /**
     * @param DataProviderInterface $provider
     * @param CacheItemPoolInterface $cache
     * @param null|string $prefix
     */
    public function __construct(DataProviderInterface $provider, CacheItemPoolInterface $cache, ?string $prefix)
    {
        $this->cache = $cache;
        $this->provider = $provider;
        $this->prefix = $prefix;
    }

    /**
     * @inheritdoc
     */
    public function get(array $input): Response
    {

        $cacheItem = $this->findCache($input);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $result = $this->provider->get($input);

        if ($cacheItem !== null) {
            $cacheItem->set($result)->expiresAt((new DateTime())->modify('+1 day'));
        }

        return $result;
    }

    /**
     * @param array $input
     * @return CacheItemInterface|null
     */
    private function findCache(array $input): ?CacheItemInterface
    {
        try {
            return $this->cache->getItem($this->prefix . md5(json_encode($input)));
        } catch (InvalidArgumentException $e) {
            $this->logger->critical($e->getMessage());
        }

        return null;
    }
}
