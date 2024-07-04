<?php

namespace OramaCloud\Client;

class Cache
{
    /**
     * @var array The cache storage
     */
    private $cache;

    public function __construct()
    {
        $this->cache = [];
    }

    /**
     * Set a value in the cache.
     * @param string $key The key under which to store the value.
     * @param mixed $value The value to store.
     */
    public function set(string $key, $value): void
    {
        $this->cache[$key] = $value;
    }

    /**
     * Get a value from the cache.
     * @param string $key The key of the value to retrieve.
     * @return mixed|null The value or null if not found.
     */
    public function get(string $key)
    {
        return $this->cache[$key] ?? null;
    }

    /**
     * Check if the cache has a key.
     * @param string $key The key to check.
     * @return bool True if the cache has the key, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    /**
     * Delete a value from the cache.
     * @param string $key The key of the value to delete.
     * @return bool True if the value was successfully deleted, false otherwise.
     */
    public function delete(string $key): bool
    {
        if ($this->has($key)) {
            unset($this->cache[$key]);
            return true;
        }
        return false;
    }

    /**
     * Clear the cache.
     */
    public function clear(): void
    {
        $this->cache = [];
    }

    /**
     * Get the size of the cache.
     * @return int The number of items in the cache.
     */
    public function size(): int
    {
        return count($this->cache);
    }
}
