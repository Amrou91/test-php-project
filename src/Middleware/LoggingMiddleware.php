<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;

class LoggingMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Log the request URL and method
        $this->logger->info('Request', [
            'url' => (string) $request->getUri(),
            'method' => $request->getMethod(),
        ]);

        // Pass the request to the next middleware/handler
        return $handler->handle($request);
    }
}
