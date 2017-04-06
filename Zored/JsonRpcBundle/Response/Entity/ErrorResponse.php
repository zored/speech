<?php


namespace Zored\JsonRpcBundle\Response\Entity;


class ErrorResponse extends AbstractResponse
{
    /**
     * @var Error
     */
    protected $error;

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Error $error
     * @return AbstractResponse
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }
}