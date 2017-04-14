<?php

namespace Zored\SpeechBundle\Test\Functional\JsonRpc\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Example of parameter that will be deserialized and passed to service.
 * Kinda DTO.
 */
class Person
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type("string")
     *
     * @var string
     */
    protected $name;

    /**
     * @Assert\GreaterThan(18)
     * @Serializer\Type("integer")
     *
     * @var int
     */
    protected $age;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     *
     * @return Person
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }
}
