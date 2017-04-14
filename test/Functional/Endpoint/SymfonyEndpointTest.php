<?php

namespace Zored\SpeechBundle\Test\Functional\Endpoint;

use Zored\SpeechBundle\Test\Functional\Kernel\AppKernel;

class SymfonyEndpointTest extends AbstractEndpointTest
{
    /**
     * {@inheritdoc}
     */
    protected function createEndpoint()
    {
        $kernel = new AppKernel('test', false);
        $kernel->boot();

        return $kernel->getContainer()->get('zored.speech.endpoint');
    }
}
