<?php

namespace Zored\SpeechBundle\Endpoint\Context;

interface ServiceContextInterface extends ContextInterface
{
    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasServiceId($id);
}
