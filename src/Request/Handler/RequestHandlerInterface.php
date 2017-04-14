<?php

namespace Zored\SpeechBundle\Request\Handler;

use Zored\SpeechBundle\Endpoint\Context\ContextInterface;
use Zored\SpeechBundle\Request\Entity\Request;
use Zored\SpeechBundle\Response\Entity\AbstractResponse;

interface RequestHandlerInterface
{
    /**
     * Converts JSON-RPC request to response.
     *
     * @param Request          $request
     * @param ContextInterface $context
     *
     * @return AbstractResponse
     */
    public function handle(Request $request, ContextInterface $context = null);
}
