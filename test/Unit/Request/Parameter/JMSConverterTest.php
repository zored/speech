<?php

namespace Zored\SpeechBundle\Test\Unit\Request\Parameter;

use JMS\Serializer\SerializerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zored\SpeechBundle\Request\Parameter\JMSConverter;
use Zored\SpeechBundle\Test\TestCase;
use Zored\SpeechBundle\Test\Unit\Request\Handler\MockService;

/**
 * @covers \Zored\SpeechBundle\Request\Parameter\JMSConverter
 */
class JMSConverterTest extends TestCase
{
    const DESERIALIZED_VALUE = -1;

    /**
     * @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var JMSConverter
     */
    private $jmsConverter;

    /**
     * @var MockService
     */
    private $service;

    protected function setUp()
    {
        $this->service = new MockService();
        $this->serializer = $this->mockSerializer();
        $this->jmsConverter = new JMSConverter(
            $this->serializer,
            null,
            $this->mockValidator()
        );
    }

    /**
     * @dataProvider  dataConvert
     *
     * @param string $method
     * @param array  $before
     * @param array  $after
     */
    public function testConvert($method, array $before, array $after)
    {
        $this->serializer->method('deserialize')->willReturn(self::DESERIALIZED_VALUE);
        self::assertEquals($after, $this->jmsConverter->convert($this->service, $method, $before));
    }

    /**
     * @expectedException \Zored\SpeechBundle\Exception\ErrorException
     * @expectedExceptionMessage Parameter 'a' is required.
     */
    public function testNotEnoughArguments()
    {
        $this->jmsConverter->convert($this->service, 'paramsMethod', []);
    }

    /**
     * @expectedException \Zored\SpeechBundle\Exception\ErrorException
     * @expectedExceptionMessage Too much parameters.
     */
    public function testTooMuchArguments()
    {
        $this->jmsConverter->convert($this->service, 'paramsMethod', [1, [], 1, 1]);
    }

    public function dataConvert()
    {
        return [
            'positional_array' => [
                'method' => 'paramsMethod',
                'before' => [
                    'a' => 1,
                    'b' => [
                        'name' => 'Bob',
                        'age' => 18,
                    ],
                    'c' => 12,
                ],
                'after' => [
                    1,
                    self::DESERIALIZED_VALUE,
                    12,
                ],
            ],
            'assoc_array' => [
                'method' => 'paramsMethod',
                'before' => [
                    1,
                    [
                        'name' => 'Bob',
                        'age' => 18,
                    ],
                    12,
                ],
                'after' => [
                    1,
                    self::DESERIALIZED_VALUE,
                    12,
                ],
            ],
            [
                'method' => 'nameMethod',
                'before' => ['hello'],
                'after' => ['hello'],
            ],
        ];
    }

    /**
     * @return SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function mockSerializer()
    {
        return $this->getMock(SerializerInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CacheItemPoolInterface
     */
    private function mockCache()
    {
        return $this->getMock(CacheItemPoolInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ValidatorInterface
     */
    private function mockValidator()
    {
        return $this->getMock(ValidatorInterface::class);
    }
}
