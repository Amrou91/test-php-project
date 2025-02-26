<?php

namespace App\Controllers;

use Ramsey\Uuid\Uuid;

class UuidController {
    public function generateUuid() {
        // Generate a UUID
        $uuid = Uuid::uuid4()->toString();

        // Return the response as JSON
        header('Content-Type: application/json');
        echo json_encode(['uuid' => $uuid]);
        exit;
    }
}
