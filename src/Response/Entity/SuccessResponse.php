<?php

namespace Zored\SpeechBundle\Response\Entity;

class SuccessResponse extends AbstractResponse
{
    /**
     * @var mixed
     */
    protected $result;

    /**
     * SuccessResponse constructor.
     *
     * @param mixed $result
     * @param null  $id
     */
    public function __construct($result = null, $id = null)
    {
        parent::__construct($id);
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     *
     * @return AbstractResponse
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getInfo()
    {
        return $this->getResult();
    }
}
