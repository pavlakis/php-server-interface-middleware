<?php

/**
 * A middleware to check the PHP-SAPI type.
 *
 * Pass a response object for action to take when whitelist not found. Defaults to 403.
 *
 * @link        https://github.com/pavlakis/php-server-interface-middleware
 * @copyright   Copyright © 2016 Antonis Pavlakis
 * @author      Antonis Pavlakis
 * @license     https://github.com/pavlakis/php-server-interface-middleware/blob/master/LICENSE (BSD 3-Clause License)
 */

namespace Pavlakis\Middleware\Server;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Sapi
{
    /**
     * @var array
     */
    private $serverWhiteList = [];

    /**
     * @var ResponseInterface
     */
    private $notFoundResponse;

    /**
     * Sapi constructor.
     * @param array $serverWhiteList
     * @param ResponseInterface $notFoundResponse
     */
    public function __construct(array $serverWhiteList, ResponseInterface $notFoundResponse = null)
    {
        $this->serverWhiteList = array_map('strtolower', $serverWhiteList);
        $this->notFoundResponse = $notFoundResponse;
    }

    /**
     * Invoke middleware
     *
     * @param  ServerRequestInterface   $request  PSR7 request object
     * @param  ResponseInterface        $response PSR7 response object
     * @param  callable                 $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {

        if (!in_array(php_sapi_name(), $this->serverWhiteList)) {
            $response = $response->withStatus(403);
            if (!is_null($this->notFoundResponse)) {
                $response = $this->notFoundResponse;
            }
        }

        return $next($request, $response);
    }
}