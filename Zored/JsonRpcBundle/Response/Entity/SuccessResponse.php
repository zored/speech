<?php


namespace Zored\JsonRpcBundle\Response\Entity;


class SuccessResponse extends AbstractResponse
{
    /**
     * @var mixed
     */
    protected $result;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return AbstractResponse
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }
}