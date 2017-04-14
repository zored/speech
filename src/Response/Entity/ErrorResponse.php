<?php

namespace Zored\SpeechBundle\Response\Entity;

class ErrorResponse extends AbstractResponse
{
    /**
     * @var Error
     */
    protected $error;

    /**
     * ErrorResponse constructor.
     *
     * @param Error  $error
     * @param string $id
     */
    public function __construct(Error $error = null, $id = null)
    {
        parent::__construct($id);
        $this->error = $error;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Error $error
     *
     * @return AbstractResponse
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    public function getInfo()
    {
        return $this->getError();
    }
}
