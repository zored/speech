<?php


namespace Zored\JsonRpcBundle\Response\Entity;


use JMS\Serializer\Annotation as Serializer;
use Zored\JsonRpcBundle\Response\ErrorCodeInterface;

class Error
{
    /**
     * @Serializer\Type("integer")
     * @var int
     */
    protected $code = ErrorCodeInterface::INTERNAL_ERROR;

    /**
     * @Serializer\Type("string")
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $data;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
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
     * @return Error
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}