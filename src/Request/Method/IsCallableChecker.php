<?php

namespace Zored\SpeechBundle\Request\Method;

class IsCallableChecker implements MethodAccessCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable($object, $method)
    {
        return substr($method, 0, 1) != '_' && is_callable([$object, $method]);
    }
}
