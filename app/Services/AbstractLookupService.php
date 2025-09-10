<?php

namespace App\Services;

use App\Contracts\LookupService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;

abstract class AbstractLookupService implements LookupService
{
    protected string $keyType;
    protected string $key;

    /**
     * Cache prefix (service-specific)
     */
    abstract protected function cachePrefix(): string;

    /**
     * Return the full API URL
     */
    abstract protected function endpoint(): string;

    abstract public static function service(): string;

    /**
     * Performs lookup with caching
     */
    public function lookup(?string $username, ?string $id)
    {
        $this->setKey($username, $id);

        return Cache::remember(
            $this->cacheKey(),
            now()->addMinutes($this->cacheTTL()),
            fn() => $this->fetchFromApi()
        );
    }


    /**
     * Set keyType and key based on input
     */
    protected function setKey(?string $username, ?string $id): void
    {
        $this->keyType = $username ? 'username' : 'id';
        $this->key = $username ?? $id;
    }

    /**
     * Build the cache key
     */
    public function cacheKey(): string
    {
        return join(':', [$this->cachePrefix(), $this->keyType, $this->key]);
    }

    private function cacheTTL()
    {
        return config('lookup.cache.TTL', 10);
    }

    /**
     * Fetch the resource from the actual API
     * @throws RequestException
     */
    protected function fetchFromApi()
    {
        $url = $this->endpoint();

        try {
            $response = Http::get($url)->throw();
            return json_decode($response->body());
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }
}
