<?php


namespace Zored\JsonRpcBundle\Request\Handler;


use Zored\JsonRpcBundle\Request\Entity\Request;
use Zored\JsonRpcBundle\Response\Entity\AbstractResponse;

interface RequestHandlerInterface
{
    /**
     * Converts JSON-RPC request to response.
     *
     * @param Request $request
     * @param array $context
     * @return AbstractResponse
     */
    public function handle(Request $request, array $context = []);
}