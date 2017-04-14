<?php

namespace Zored\SpeechBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function jsonRpcAction(Request $request)
    {
        $context = $this->get('zored.speech.controller.context');
        $endpoint = $this->get('zored.speech.endpoint');
        $response = $endpoint->handle($request->getContent(), $context);

        return new Response($response, 200, ['Content-Type' => 'application/json']);
    }
}
