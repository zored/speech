<?php

namespace Zored\SpeechBundle\Test\Unit\Request\Method;

use Zored\SpeechBundle\Request\Method\IsCallableChecker;
use Zored\SpeechBundle\Test\TestCase;
use Zored\SpeechBundle\Test\Unit\Request\Handler\MockService;

class IsCallableCheckerTest extends TestCase
{
    /**
     * @var IsCallableChecker
     */
    private $isCallableChecker;

    /**
     * @var MockService
     */
    private $service;

    protected function setUp()
    {
        $this->isCallableChecker = new IsCallableChecker();
        $this->service = new MockService();
    }

    public function testCheckSuccess()
    {
        $this->assertTrue($this->isCallableChecker->isAvailable($this->service, 'mockMethod'));
    }

    public function testCheckFail()
    {
        $this->assertFalse($this->isCallableChecker->isAvailable($this->service, 'privateMethod'));
    }
}
