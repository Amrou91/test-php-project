<?php

namespace App\Middleware;

use Neomerx\Cors\CorsManager;
use Neomerx\Cors\Options;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class CorsMiddleware implements MiddlewareInterface
{
    private $corsManager;

    public function __construct()
    {
        // Define CORS options
        $this->corsManager = new CorsManager([
            Options::ALLOW_ORIGIN => ['*'], // Allow all origins (change this to specific origins as needed)
            Options::ALLOW_METHODS => ['GET', 'POST', 'OPTIONS'], // Allowed HTTP methods
            Options::ALLOW_HEADERS => ['Content-Type', 'Authorization'], // Allowed headers
            Options::EXPOSE_HEADERS => [], // Headers to expose
            Options::MAX_AGE => 3600, // Maximum age for caching preflight requests
        ]);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Handle CORS preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            return $this->corsManager->handlePreflight($request);
        }

        // Handle the request
        $response = $handler->handle($request);

        // Add CORS headers to the response
        return $this->corsManager->addHeaders($response, $request);
    }
}
