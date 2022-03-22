<?php

namespace App\Middleware;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ConvertGetRequestParams implements MiddlewareInterface
{
    /**
     * Process method.
     *
     * @param ServerRequestInterface $request The request.
     * @param RequestHandlerInterface $handler The request handler.
     * @return ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();
        $params = $this->_clean($params);
        $request = $request->withQueryParams($params);

        $params = $request->getAttribute('params');
        $params['pass'] = $this->_clean( $params['pass']);
        /** @var ServerRequest $request */
        $request = $request->withAttribute('params', $params);
        Router::setRequest($request);

        return $handler->handle($request);
    }

    private function _clean(array $params) {
        foreach ($params as $key => $param) {
            if ($param === 'false') {
                $params[$key] = false;
            } else if ($param === 'true') {
                $params[$key] = true;
            } else if ($param === 'undefined') {
                $params[$key] = null;
            } else if ($param === '') {
                $params[$key] = null;
            }
        }

        return $params;
    }
}
