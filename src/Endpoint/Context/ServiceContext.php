<?php

namespace Zored\SpeechBundle\Endpoint\Context;

class ServiceContext implements ServiceContextInterface
{
    /**
     * @var string[]
     */
    private $ids = [];

    /**
     * @param \string[] $ids
     */
    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    /**
     * {@inheritdoc}
     */
    public function hasServiceId($id)
    {
        return in_array($id, $this->ids);
    }
}
