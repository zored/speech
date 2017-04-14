<?php

namespace Zored\SpeechBundle\Response\Entity;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints\EqualTo;

abstract class AbstractResponse
{
    /**
     * @EqualTo("2.0")
     * @SerializedName("jsonrpc")
     * @Type("string")
     *
     * @var string
     */
    protected $version = '2.0';

    /**
     * @Type("string")
     *
     * @var int
     */
    protected $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return float
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param float $version
     *
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
     *
     * @return AbstractResponse
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    abstract public function getInfo();
}
