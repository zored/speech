<?php


namespace Zored\JsonRpcBundle\Request\Entity;


class Method
{
    /**
     * @var bool
     */
    protected $public;

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param bool $public
     * @return Method
     */
    public function setPublic($public)
    {
        $this->public = $public;
        return $this;
    }
    
    
}