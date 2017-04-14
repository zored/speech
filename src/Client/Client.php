<?php

namespace Zored\SpeechBundle\Client;

use Zored\SpeechBundle\Exception\ClientErrorResponseException;

class Client
{
    /**
     * @var ClientAdapterInterface
     */
    private $adapter;

    public function __construct(ClientAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __call($name, $arguments)
    {
        return $this($name, $arguments[0]);
    }

    /**
     * @param $method
     * @param array $params
     */
    public function __invoke($method, array $params)
    {
        $id = $this->generateId();
        $content = json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'method' => $method,
            'params' => $params,
        ]);
        $response = $this->adapter->send($content);
        $response = json_decode($response, true);

        if ($response['id'] != $id) {
            throw new ClientErrorResponseException('Wrong resposne ID.');
        }

        if (isset($response['error'])) {
            throw new ClientErrorResponseException($response['error']['message']);
        }

        return $response['result'];
    }

    private function generateId()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
