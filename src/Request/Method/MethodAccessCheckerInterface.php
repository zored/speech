<?php

namespace Zored\SpeechBundle\Request\Method;

use Zored\SpeechBundle\Request\Entity\Method;

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
