<?php

namespace Zored\SpeechBundle\Test\Functional\Endpoint;

use Zored\SpeechBundle\Endpoint\EndpointBuilder;
use Zored\SpeechBundle\Subscriber\UUID4Subscriber;
use Zored\SpeechBundle\Test\Functional\JsonRpc\Greeter;

/**
 * @group functional
 */
class LibraryEndpointTest extends AbstractEndpointTest
{
    /**
     * {@inheritdoc}
     */
    protected function createEndpoint()
    {
        return (new EndpointBuilder())
            ->addSubscriber(new UUID4Subscriber())
            ->addService('your.service', Greeter::class)
            ->getEndpoint();
    }
}
