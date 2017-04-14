<?php

namespace Zored\SpeechBundle\Test\Functional\JsonRpc\DTO;

use JMS\Serializer\Annotation as Serializer;
use Zored\SpeechBundle\Request\Entity\ParamsHolderInterface;
use Zored\SpeechBundle\Test\Functional\JsonRpc\DTO\Person;

class PersonHolder implements ParamsHolderInterface
{
    /**
     * @Serializer\Type("Zored\SpeechBundle\Test\Functional\JsonRpc\DTO\Person")
     *
     * @var Person
     */
    protected $person;

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     *
     * @return PersonHolder
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }
}
