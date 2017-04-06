<?php


namespace Zored\JsonRpcBundle\Request\Entity;


use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    /**
     * @Assert\EqualTo("2.0")
     * @Serializer\SerializedName("jsonrpc")
     * @Type("float")
     * @var float
     */
    protected $version;

    /**
     * @Type("string")
     * @var int
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @Type("string")
     * @var string
     */
    protected $method;


    /**
     * @Type("array")
     * @var array
     */
    protected $params;

    /**
     * @return float
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param float $version
     * @return Request
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Request
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return Request
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return Request
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
}