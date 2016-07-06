<?php

namespace Pavlakis\Middleware\Server\Tests;


use Pavlakis\Middleware\Server\Sapi;
use Slim\Http\Body;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

class SapiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Taken from: https://github.com/slimphp/Slim-HttpCache/blob/master/tests/CacheTest.php
     * @return Request
     */
    public function requestFactory()
    {
        $uri = Uri::createFromString('https://example.com:443/foo/bar?abc=123');
        $headers = new Headers();
        $cookies = [];
        $serverParams = [];
        $body = new Body(fopen('php://temp', 'r+'));
        return new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
    }

    public function testACliRequestIsAllowed()
    {
        $whiteList = ["cli"];

        $sapi = new Sapi($whiteList);

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var  ResponseInterface $res */
        $res = $sapi($req, $res, $next);

        $this->assertEquals(200, $res->getStatusCode());
    }

    public function testACliRequestIsNotAllowedDefaultsTo403()
    {
        $whiteList = ["apache"];

        $sapi = new Sapi($whiteList);

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var  ResponseInterface $res */
        $res = $sapi($req, $res, $next);

        $this->assertEquals(403, $res->getStatusCode());
    }

    public function testACliRequestIsNotAllowedReturnsPassedResponse()
    {
        $whiteList = ["apache"];
        $sapiRes = new Response();
        $sapiRes = $sapiRes->withStatus(500);

        $sapi = new Sapi($whiteList, $sapiRes);

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var  ResponseInterface $res */
        $res = $sapi($req, $res, $next);

        $this->assertEquals(500, $res->getStatusCode());
    }
}