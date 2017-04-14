<?php

namespace Zored\SpeechBundle\Test\Unit\Endpoint;

use Zored\SpeechBundle\Endpoint\Context\ContextInterface;
use Zored\SpeechBundle\Endpoint\Endpoint;
use Zored\SpeechBundle\Request\Passer\RequestPasserInterface;

/**
 * @covers \Zored\SpeechBundle\Endpoint\Endpoint
 */
class EndpointTest extends \PHPUnit_Framework_TestCase
{
    const JSON_INVALID = 'invalid';
    const JSON_VALID = '{}';

    /**
     * @var Endpoint
     */
    private $endpoint;

    protected function setUp()
    {
        $this->endpoint = new Endpoint();
    }

    public function testHandleError()
    {
        $response = $this->endpoint->handle(self::JSON_INVALID, $this->mockContext());
        $this->assertSame('{"jsonrpc":"2.0","error":{"code":-32700,"message":"Could not parse JSON."}}', $response);
    }

    public function testHandleWithoutPassers()
    {
        $response = $this->endpoint->handle(self::JSON_VALID, $this->mockContext());
        $this->assertSame('{"jsonrpc":"2.0","error":{"code":-32603,"message":"Empty response for your request."}}', $response);
    }

    public function testHandleWithPasser()
    {
        $passer = $this->mockPasser();
        $passer
            ->method('suitsArray')
            ->will($this->returnValue(true));
        $passer
            ->method('pass')
            ->will($this->returnValue('test'));

        $this->endpoint->setPassers([$passer]);
        $response = $this->endpoint->handle(self::JSON_VALID, $this->mockContext());
        $this->assertSame('test', $response);
    }

    /**
     * @return ContextInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockContext()
    {
        return $this->getMockBuilder(ContextInterface::class)->getMock();
    }

    /**
     * @return RequestPasserInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockPasser()
    {
        return $this->getMockBuilder(RequestPasserInterface::class)->getMock();
    }
}
