<?php

namespace Zored\SpeechBundle\Request\Passer;

use Zored\SpeechBundle\Endpoint\Context\ContextInterface;

interface RequestPasserInterface
{
    /**
     * Check that type suites array.
     *
     * @param array $data
     *
     * @return bool
     */
    public function suitsArray(array $data);

    /**
     * Handle array and return array response.
     *
     * @param string           $requestContent
     * @param ContextInterface $context
     *
     * @return string
     */
    public function pass($requestContent, ContextInterface $context = null);
}
