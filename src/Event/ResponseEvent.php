<?php


namespace Zored\SpeechBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Zored\SpeechBundle\Response\Entity\AbstractResponse;

class ResponseEvent extends Event
{
    /**
     * @var AbstractResponse
     */
    private $response;

    public function __construct(AbstractResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return AbstractResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param AbstractResponse $response
     * @return ResponseEvent
     */
    public function setResponse(AbstractResponse $response)
    {
        $this->response = $response;
        return $this;
    }

}