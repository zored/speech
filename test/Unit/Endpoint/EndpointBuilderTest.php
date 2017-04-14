<?php

namespace Zored\SpeechBundle\Test\Unit\Endpoint;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zored\SpeechBundle\Endpoint\Endpoint;
use Zored\SpeechBundle\Endpoint\EndpointBuilder;
use Zored\SpeechBundle\Test\TestCase;

class EndpointBuilderTest extends TestCase
{
    /**
     * @var EndpointBuilder
     */
    private $endpointBuilder;

    protected function setUp()
    {
        $this->endpointBuilder = new EndpointBuilder();
    }

    public function testAddServices()
    {
        $this->assertSame(
            $this->endpointBuilder,
            $this->endpointBuilder->addService('a', 'b')
        );
    }

    public function testAddSubscriber()
    {
        $this->assertSame(
            $this->endpointBuilder,
            $this->endpointBuilder->addSubscriber($this->mockSubscriber())
        );
    }

    public function testGetEndpoint()
    {
        $endpoint = $this->endpointBuilder->getEndpoint();
        self::assertInstanceOf(Endpoint::class, $endpoint);
    }

    /**
     * @return EventSubscriberInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockSubscriber()
    {
        return $this->getMockBuilder(EventSubscriberInterface::class)->getMock();
    }
}
