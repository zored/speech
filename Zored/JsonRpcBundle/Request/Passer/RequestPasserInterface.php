<?php


namespace Zored\JsonRpcBundle\Request\Passer;


interface RequestPasserInterface
{
    /**
     * Check that type suites array.
     *
     * @param array $data
     * @return bool
     */
    public function suitsArray(array $data);

    /**
     * Handle array and return array response.
     *
     * @param string $json
     * @param array $context
     * @return string
     */
    public function pass($json, array $context = []);
}