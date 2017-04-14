<?php

namespace Zored\SpeechBundle\Request\Parameter;

interface ParameterConverterInterface
{
    /**
     * Convert parameters for method from one format to another one.
     *
     * @param $object
     * @param $method
     * @param array $requestParameters
     */
    public function convert($object, $method, array $requestParameters = null);
}
