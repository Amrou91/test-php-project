<?php

// src/Controllers/UuidController.php

namespace App\Controllers;

// Import necessary classes
use Ramsey\Uuid\Uuid;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

// Define the UuidController class
class UuidController
{
    // Declare a private property for the cache
    private $cache;

    // Constructor to initialize the cache
    public function __construct()
    {
        // Create a FilesystemAdapter for caching, specifying the cache directory
        // The cache will be stored in the 'cache' directory two levels up from this file
        $this->cache = new FilesystemAdapter('', 0, __DIR__ . '/../Cache');
    }

    // Method to generate a UUID
    public function generateUuid()
    {
        // Create a unique cache key based on the current timestamp
        $cacheKey = 'uuid_cache_' . date('Ymd_His'); // e.g., uuid_cache_20250226_123456

        // Check if a UUID is already stored in the cache with the key 'uuid_cache'
        $cachedUuid = $this->cache->getItem($cacheKey);

        // If the cached UUID exists (a cache hit), return it
        if ($cachedUuid->isHit()) {
            return $cachedUuid->get(); // Return the cached UUID
        }

        // If no cached UUID exists, generate a new UUID
        $uuid = Uuid::uuid4()->toString();

        // Store the generated UUID in the cache for 60 seconds
        $cachedUuid->set($uuid); // Set the value of the cached item
        $cachedUuid->expiresAfter(60); // Set the expiration time for the cache
        $this->cache->save($cachedUuid); // Save the cached item

        // Set the content type of the response to application/json
        header('Content-Type: application/json');
        // Return the generated UUID in JSON format
        echo json_encode(['uuid' => $uuid]);
        exit; // Exit the script
    }
}
