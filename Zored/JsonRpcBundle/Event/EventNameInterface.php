<?php


namespace Zored\JsonRpcBundle\Event;

/**
 * Interface EventNameInterface
 * @package Zored\JsonRpcBundle\Event
 */
interface EventNameInterface
{
    /** Occurs when error response retured. */
    const ERROR_RESPONSE = 'zored.json_rpc.error';

    /** Occurs when request is deserialized. */
    const REQUEST_DESERIALIZED = 'zored.json_rpc.request';
}