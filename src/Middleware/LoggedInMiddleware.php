<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Core\Configure;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Company middleware
 */
class LoggedInMiddleware implements MiddlewareInterface
{
    /**
     * Process method.
     *
     * @param ServerRequestInterface $request The request.
     * @param RequestHandlerInterface $handler The request handler.
     * @throws Exception
     * @return ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('params');
        $identity = $request->getAttribute('identity');

        if (!$identity && $route['path'] !== '/login') {
            throw new Exception('Not Logged In', 401);
        }

        return $handler->handle($request);
    }
}
