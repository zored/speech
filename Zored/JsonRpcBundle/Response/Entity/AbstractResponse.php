<?php


namespace Zored\JsonRpcBundle\Response\Entity;


use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints\EqualTo;

abstract class AbstractResponse
{

    /**
     * @EqualTo("2.0")
     * @SerializedName("jsonrpc")
     * @Type("float")
     * @var float
     */
    protected $version = '2.0';

    /**
     * @Type("string")
     * @var int
     */
    protected $id;

    /**
     * @return float
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param float $version
     * @return AbstractResponse
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AbstractResponse
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}