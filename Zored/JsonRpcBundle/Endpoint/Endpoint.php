<?php

namespace Zored\JsonRpcBundle\Endpoint;


use Symfony\Component\HttpFoundation\Response;
use Zored\JsonRpcBundle\Endpoint\AbstractEndpoint;
use Zored\JsonRpcBundle\Request\Passer\RequestPasserInterface;

class Endpoint implements EndpointInterface
{
    /**
     * @var RequestPasserInterface[]
     */
    private $types;

    /**
     * @param RequestPasserInterface[] $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * {@inheritDoc}
     */
    public function handle($json, array $context = [])
    {
        $content = $this->getContent($json, $context);
        return new Response(
            $content,
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @param $json
     * @param array $context
     * @return string
     */
    private function getContent($json, array $context = [])
    {
        $data = json_decode($json, true);
        if (json_last_error()) {
            throw new \RuntimeException('Could not parse JSON: ' . json_last_error_msg());
        }

        $content = null;
        foreach ($this->types as $type) {
            if (!$type->suitsArray($data)) {
                continue;
            }
            $content = $type->pass($json, $context);
            break;
        }

        if (empty($content)) {
            throw new \RuntimeException('Empty response for RPC query.');
        }
        return $content;
    }
}