<?php


namespace Zored\JsonRpcBundle\Request\Passer;

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
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function pass($json, array $context = [])
    {
        $result = [];
        foreach (json_decode($json, true) as $item) {
            $result[] = $this->single->pass(json_encode($item), $context);
        }
        $result = implode(', ', $result);
        return "[$result]";
    }

}