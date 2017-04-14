<?php

namespace Zored\SpeechBundle\Response\Entity;

use JMS\Serializer\Annotation as Serializer;
use Zored\SpeechBundle\Response\ErrorCodeInterface;

class Error
{
    /**
     * @Serializer\Type("integer")
     *
     * @var int
     */
    protected $code = ErrorCodeInterface::INTERNAL_ERROR;

    /**
     * @Serializer\Type("string")
     *
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $data;

    /**
     * Error constructor.
     *
     * @param int    $code
     * @param string $message
     * @param array  $data
     */
    public function __construct($message = '', $code = ErrorCodeInterface::INTERNAL_ERROR, array $data = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return Error
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Error
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return Error
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
