<?php

namespace Zored\SpeechBundle\Test\Functional\JsonRpc;

use Zored\SpeechBundle\Request\Parameter\ParameterConverterInterface;
use Zored\SpeechBundle\Response\Entity\AbstractResponse;
use Zored\SpeechBundle\Response\Entity\Error;
use Zored\SpeechBundle\Response\Entity\ErrorResponse;
use Zored\SpeechBundle\Test\Functional\JsonRpc\DTO\Person;

class Greeter
{
    /**
     * Outputs greeting message.
     *
     * $person is deserialized from JSON to object with:
     *
     * @see ParameterConverterInterface
     *
     * @param Person $person
     *
     * @return AbstractResponse|array
     */
    public function greet(Person $person)
    {
        return [
            'message' => "Hello, {$person->getName()}",
        ];
    }

    /**
     * Error output example.
     *
     * @return AbstractResponse|array
     */
    public function customRedirect()
    {
        $error = (new Error('Need redirect.'))
            ->setCode(201)
            ->setData(['route' => 'greet']);

        return (new ErrorResponse())->setError($error);
    }
}
