<?php


namespace Zored\JsonRpcBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Zored\JsonRpcBundle\Request\Entity\Request;

class RequestEvent extends Event
{

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return RequestEvent
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
}