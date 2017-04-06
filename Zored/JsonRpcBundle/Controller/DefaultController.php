<?php


namespace Zored\JsonRpcBundle\Controller;


use Zored\JsonRpcBundle\Endpoint\EndpointInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function jsonRpcAction(Request $request)
    {
        $endpoint = $this->get('lamoda.json_rpc.endpoint');
        $context = $this->getParameter('lamoda.json_rpc.controller.context');
        return $endpoint->handle($request->getContent(), $context);
    }
}