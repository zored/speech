<?php

namespace Zored\SpeechBundle\Test\Unit\Request\Passer;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zored\SpeechBundle\Endpoint\Context\ServiceContextInterface;
use Zored\SpeechBundle\Request\Entity\Request;
use Zored\SpeechBundle\Request\Handler\RequestHandlerInterface;
use Zored\SpeechBundle\Request\Passer\SingleRequestPasser;
use Zored\SpeechBundle\Test\TestCase;

class SingleRequestPasserTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ValidatorInterface
     */
    private $validator;

    /**
     * @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var SingleRequestPasser
     */
    private $singleRequestPasser;

    public function testPass()
    {
        $this->serializer->method('deserialize')->willReturn($this->mockRequest());
        $this->validator->method('validate')->willReturn([]);
        $this->serializer->method('serialize')->willReturn('response');

        $this->assertSame(
            'response',
            $this->singleRequestPasser->pass('request', $this->mockContext())
        );
    }

    protected function setUp()
    {
        $this->serializer = $this->mockSerializer();
        $this->validator = $this->mockValidator();
        $this->requestHandler = $this->mockRequestHandler();
        $this->singleRequestPasser = new SingleRequestPasser(
            $this->serializer,
            $this->validator,
            $this->requestHandler,
            Request::class
        );
    }

    /**
     * @return SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function mockSerializer()
    {
        return $this->getMock(SerializerInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ValidatorInterface
     */
    private function mockValidator()
    {
        return $this->getMock(ValidatorInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RequestHandlerInterface
     */
    private function mockRequestHandler()
    {
        return $this->getMock(RequestHandlerInterface::class);
    }

    /**
     * @return Request
     */
    private function mockRequest()
    {
        $request = (new Request())
            ->setId('id_1')
            ->setMethod('service1:mockMethod')
            ->setParams(['param_1' => 1]);

        return $request;
    }

    /**
     * @return ServiceContextInterface | \PHPUnit_Framework_MockObject_MockObject|ServiceContextInterface
     */
    private function mockContext()
    {
        return $this->getMock(ServiceContextInterface::class);
    }
}
