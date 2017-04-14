<?php

namespace Zored\SpeechBundle\Test\Unit\Request\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Zored\SpeechBundle\Endpoint\Context\ServiceContextInterface;
use Zored\SpeechBundle\Request\Entity\Request;
use Zored\SpeechBundle\Request\Handler\ServiceHandler;
use Zored\SpeechBundle\Request\Method\MethodAccessCheckerInterface;
use Zored\SpeechBundle\Request\Parameter\ParameterConverterInterface;
use Zored\SpeechBundle\Response\Entity\Error;
use Zored\SpeechBundle\Response\Entity\ErrorResponse;
use Zored\SpeechBundle\Response\Entity\SuccessResponse;
use Zored\SpeechBundle\Test\TestCase;

/**
 * Class ServiceHandlerTest.
 */
class ServiceHandlerTest extends TestCase
{
    /**
     * @var ContainerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * @var MethodAccessCheckerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $methodAccessChecker;

    /**
     * @var ParameterConverterInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $parameterConverter;

    /**
     * @var ServiceHandler
     */
    private $serviceHandler;

    protected function setUp()
    {
        $this->container = $this->mockContainer();
        $this->methodAccessChecker = $this->mockMethodAccessChecker();
        $this->parameterConverter = $this->mockParamConverter();
        $this->serviceHandler = new ServiceHandler(
            $this->container,
            $this->methodAccessChecker,
            $this->parameterConverter
        );
    }

    /**
     * @dataProvider dataSuccessHandle
     *
     * @param Request $request
     * @param bool    $methodAvailable
     * @param array   $parameters
     * @param bool    $hasServiceId
     * @param string  $responseClass
     * @param mixed   $info
     */
    public function testSuccessHandle(Request $request, $methodAvailable, $parameters, $hasServiceId, $responseClass, $info)
    {
        // Dependencies:
        $mockService = $this->mockService();
        $context = $this->mockContext();

        // Method calls:
        $this->methodAccessChecker->method('isAvailable')->willReturn($methodAvailable);
        $this->container->method('get')->willReturn($mockService);
        $this->parameterConverter->method('convert')->willReturn($parameters);
        $context->method('hasServiceId')->willReturn($hasServiceId);

        // Call:
        $response = $this->serviceHandler->handle($request, $context);

        // Assertions:
        $this->assertInstanceOf($responseClass, $response, print_r($response, true));
        $this->assertSame($request->getId(), $response->getId());
        $this->assertEquals($info, $response->getInfo());
    }

    public function dataSuccessHandle()
    {
        $request = $this->mockRequest()->setMethod('s:mockMethod');
        $nameRequest = $this->mockRequest()->setMethod('s:nameMethod');

        return [
            'success' => [
                'request' => $request,
                'methodAvailable' => true,
                'parameters' => [],
                'hasServiceId' => true,
                'responseClass,' => SuccessResponse::class,
                'info' => $this->mockService()->mockMethod(),
            ],
            'success_parameters' => [
                'request' => $nameRequest,
                'methodAvailable' => true,
                'parameters' => ['name' => 'bob'],
                'hasServiceId' => true,
                'responseClass,' => SuccessResponse::class,
                'info' => $this->mockService()->nameMethod('bob'),
            ],
            'not_available' => [
                'request' => $request,
                'methodAvailable' => false,
                'parameters' => [],
                'hasServiceId' => true,
                'responseClass,' => ErrorResponse::class,
                'info' => new Error("Method 'mockMethod' is not avaliable in 's'.", -32601),
            ],
            'no_service' => [
                'request' => $request,
                'methodAvailable' => true,
                'parameters' => [],
                'hasServiceId' => false,
                'responseClass,' => ErrorResponse::class,
                'info' => new Error("Service 's' doesn't exist.", -32601),
            ],
        ];
    }

    /**
     * @expectedException \LogicException
     */
    public function testNoContext()
    {
        $this->serviceHandler->handle($this->mockRequest());
    }

    /**
     * @return MethodAccessCheckerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockMethodAccessChecker()
    {
        return $this->getMock(MethodAccessCheckerInterface::class);
    }

    /**
     * @return ContainerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockContainer()
    {
        return $this->getMock(ContainerInterface::class);
    }

    /**
     * @return ParameterConverterInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockParamConverter()
    {
        return $this->getMock(ParameterConverterInterface::class);
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

    /**
     * @return MockService
     */
    private function mockService()
    {
        return new MockService();
    }
}
