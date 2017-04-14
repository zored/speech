<?php

namespace Zored\SpeechBundle\Test\Functional\Endpoint;

use Zored\SpeechBundle\Endpoint\Context\ContextInterface;
use Zored\SpeechBundle\Endpoint\Context\ServiceContext;
use Zored\SpeechBundle\Endpoint\EndpointInterface;

abstract class AbstractEndpointTest extends \PHPUnit_Framework_TestCase
{
    const SUCCESS_REQUEST = /* @lang JSON */<<<JSON
{
    "jsonrpc": 2.0,
    "id": "456daEFb-5AA6-41B5-8DBC-068B05A8B201",
    "method": "your.service:greet",
    "params": {
      "person": {
          "name": "Tom",
          "age": 19
      }
    }
}
JSON;
    const SUCCESS_RESPONSE = /* @lang JSON */'{"jsonrpc":"2.0","id":"456daEFb-5AA6-41B5-8DBC-068B05A8B201","result":{"message":"Hello, Tom"}}';

    /**
     * @var EndpointInterface
     */
    private $endpoint;

    /**
     * @var ContextInterface
     */
    private $context;

    protected function setUp()
    {
        $this->endpoint = $this->createEndpoint();
        $this->context = new ServiceContext(['your.service']);
    }

    /**
     * Example of successful response results.
     *
     * @test
     * @dataProvider dataSuccess
     *
     * @param string $requestJson
     * @param string $responseJson
     */
    public function success($requestJson, $responseJson)
    {
        $response = $this->endpoint->handle($requestJson, $this->context);
        $this->assertEquals($responseJson, $response);
    }

    public function dataSuccess()
    {
        return [
            /*
             * Successful runs.
             * @see \Zored\SpeechBundle\Request\Passer\SingleRequestPasser
             */
            'success' => [
                self::SUCCESS_REQUEST,
                self::SUCCESS_RESPONSE,
            ],
            'success_holder' => [
                /* @lang JSON */<<<JSON
{
    "jsonrpc": 2.0,
    "id": "456daEFb-5AA6-41B5-8DBC-068B05A8B201",
    "method": "your.service:greetHolder",
    "params": {
      "person": {
          "name": "Tom",
          "age": 19
      }
    }
}
JSON
                ,
                /* @lang JSON */'{"jsonrpc":"2.0","id":"456daEFb-5AA6-41B5-8DBC-068B05A8B201","result":{"message":"Hello, Tom"}}',
            ],
            'success_error' => [
                /* @lang JSON */<<<JSON
{
    "jsonrpc": 2.0,
    "id": "456daEFb-5AA6-41B5-8DBC-068B05A8B201",
    "method": "your.service:customRedirect"
}
JSON
                ,
                /* @lang JSON */
                '{"jsonrpc":"2.0","error":{"code":201,"message":"Need redirect.","data":{"route":"greet"}}}',
            ],

            /*
             * Zored requires UUID4.
             * @see \Zored\SpeechBundle\Subscriber\UUID4Subscriber
             */
            'id_uuid4' => [
                /* @lang JSON */<<<JSON
{
    "jsonrpc": 2.0,
    "id": "1",
    "method": "your.service:greet",
    "params": {
      "person": {
          "name": "Tom",
          "age": 19
      }
    }
}
JSON
                ,
                /* @lang JSON */
                '{"jsonrpc":"2.0","error":{"code":-32602,"message":"Invalid params.","data":{"id":"This is not a valid UUID."}}}',
            ],

            /*
             * Batch is disabled by Zored:
             * @see \Zored\SpeechBundle\Request\Passer\BatchRequestPasser
             */
            'no_batch' => [
                /* @lang JSON */<<<JSON
[
    {
        "jsonrpc": 2.0,
        "id": "456daEFb-5AA6-41B5-8DBC-068B05A8B201",
        "method": "your.service:greet",
        "params": {
          "person": {
              "name": "Tom",
              "age": 19
          }
        }
    },
    {
        "jsonrpc": 2.0,
        "id": "456daEFb-5AA6-41B5-8DBC-068B05A8B202",
        "method": "your.service:greet",
        "params": {
          "person": {
              "name": "Bill",
              "age": 20
          }
        }
    }
]
JSON
                ,
                /* @lang JSON */
                '[{"jsonrpc":"2.0","id":"456daEFb-5AA6-41B5-8DBC-068B05A8B201","result":{"message":"Hello, Tom"}}, {"jsonrpc":"2.0","id":"456daEFb-5AA6-41B5-8DBC-068B05A8B202","result":{"message":"Hello, Bill"}}]',
            ],

            /*
             * Validation faliure.
             * @see \Zored\SpeechBundle\Test\Functional\JsonRpc\DTO\Person::$age
             */
            'validation' => [
                /* @lang JSON */<<<JSON
{
    "jsonrpc": 2.0,
    "id": "456daEFb-5AA6-41B5-8DBC-068B05A8B201",
    "method": "your.service:greet",
    "params": {
      "person": {
          "name": "Tom",
          "age": 16
      }
    }
}
JSON
                ,
                /* @lang JSON */
                '{"jsonrpc":"2.0","id":"456daEFb-5AA6-41B5-8DBC-068B05A8B201","error":{"code":-32602,"message":"Parameters validation failed.","data":{"age":"This value should be greater than 18."}}}',
            ],

            /*
             * - Invalid JSON.
             * - Empty batch.
             * @see \Zored\SpeechBundle\Endpoint\Endpoint::getContent
             */
            'invalid_json' => [
                'Invalid JSON.',
                /* @lang JSON */
                '{"jsonrpc":"2.0","error":{"code":-32700,"message":"Could not parse JSON."}}',
            ],
            'empty_batch' => [
                '[]',
                '{"jsonrpc":"2.0","error":{"code":-32603,"message":"Empty response for your request."}}',
            ],

            /*
             * Unknown method.
             * @see \Zored\SpeechBundle\Request\Handler\ServiceHandler::handle
             */
            'unknown_method' => [
                /* @lang JSON */<<<JSON
{
    "jsonrpc": 2.0,
    "id": "456daEFb-5AA6-41B5-8DBC-068B05A8B201",
    "method": "some.service:greet"
}
JSON
                ,
                /* @lang JSON */
                '{"jsonrpc":"2.0","id":"456daEFb-5AA6-41B5-8DBC-068B05A8B201","error":{"code":-32601,"message":"Service \'some.service\' doesn\'t exist."}}',
            ],
        ];
    }

    /**
     * @return EndpointInterface
     */
    abstract protected function createEndpoint();
}
