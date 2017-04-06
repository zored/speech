<?php


namespace Zored\JsonRpcBundle\Endpoint;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EndpointInterface
{
    /**
     * Handle request and return response.
     *
     * @param string $json
     * @param array $context
     * @return Response
     */
    public function handle($json, array $context = []);
}