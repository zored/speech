<?php

namespace Zored\SpeechBundle\Endpoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zored\SpeechBundle\Endpoint\Context\ContextInterface;

interface EndpointInterface
{
    /**
     * Handle request and return response.
     *
     * @param string           $json
     * @param ContextInterface $context
     *
     * @return string
     */
    public function handle($json, ContextInterface $context);
}
