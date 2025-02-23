<?php

require 'vendor/autoload.php';

use Ramsey\Uuid\Uuid;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$cache = new FilesystemAdapter();
$item = $cache->getItem('uuid');

if (!$item->isHit()) {
    $item->set(Uuid::uuid4()->toString())->expiresAfter(60);
    $cache->save($item);
}

header('Content-Type: application/json');
echo json_encode(['uuid' => $item->get()]);