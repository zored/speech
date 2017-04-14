<?php

namespace Zored\SpeechBundle\Test\Unit\Request\Handler;

use Zored\SpeechBundle\Test\Functional\JsonRpc\DTO\Person;

class MockService
{
    public function mockMethod()
    {
        return ['mock' => 'result'];
    }

    public function nameMethod($name)
    {
        return ['upper' => strtoupper($name)];
    }

    public function paramsMethod($a, Person $b, $c = null)
    {
    }

    private function privateMethod()
    {
    }
}
