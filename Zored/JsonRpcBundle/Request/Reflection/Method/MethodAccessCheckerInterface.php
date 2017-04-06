<?php


namespace Zored\JsonRpcBundle\Request\Reflection\Method;


use Zored\JsonRpcBundle\Request\Entity\Method;

interface MethodAccessCheckerInterface
{

    /**
     * Check if method is avaliable for user.
     *
     * @param $object
     * @param $method
     *
     * @returns Method
     */
    public function isAvailable($object, $method);
}