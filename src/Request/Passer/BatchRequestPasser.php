<?php

namespace Zored\SpeechBundle\Request\Passer;

use Zored\SpeechBundle\Endpoint\Context\ContextInterface;

/**
 * Passes multiple request arrays to handler.
 */
class BatchRequestPasser implements RequestPasserInterface
{
    /**
     * @var SingleRequestPasser
     */
    private $single;

    public function __construct(RequestPasserInterface $single)
    {
        $this->single = $single;
    }

    /**
     * {@inheritdoc}
     */
    public function suitsArray(array $data)
    {
        if (!isset($data[0]['jsonrpc'])) {
            return false;
        }
        foreach ($data as $item) {
            return $this->single->suitsArray($item);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function pass($requestContent, ContextInterface $context = null)
    {
        $result = [];
        foreach (json_decode($requestContent, true) as $item) {
            $result[] = $this->single->pass(json_encode($item), $context);
        }
        $result = implode(', ', $result);

        return "[$result]";
    }
}
