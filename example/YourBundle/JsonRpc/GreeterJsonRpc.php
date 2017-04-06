<?php

namespace YourBundle\JsonRpc;

use YourBundle\JsonRpc\DTO\Person;
use Zored\JsonRpcBundle\Request\Reflection\Parameter\ParameterConverterInterface;
use Zored\JsonRpcBundle\Response\Entity\AbstractResponse;
use Zored\JsonRpcBundle\Response\Entity\Error;
use Zored\JsonRpcBundle\Response\Entity\ErrorResponse;

class GreeterJsonRpc
{
    /**
     * Outputs greeting message.
     *
     * $person is deserialized from JSON to object with:
     * @see ParameterConverterInterface
     *
     * @param Person $person
     * @return AbstractResponse|array
     */
    public function greet(Person $person)
    {
        return [
            'message' => "Hello, {$person->getName()}"
        ];
    }

    /**
     * Error output example.
     *
     * @return AbstractResponse|array
     */
    public function customRedirect()
    {
        $error = (new Error())
            ->setCode(201)
            ->setData(['route' => 'greet']);

        return (new ErrorResponse())->setError($error);
    }
}