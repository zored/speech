<?php


namespace Zored\SpeechBundle\Event;

/**
 * Interface EventNameInterface
 * @package Zored\SpeechBundle\Event
 */
interface EventNameInterface
{
    /** Occurs when error response retured. */
    const ERROR_RESPONSE = 'zored.speech.error';

    /** Occurs when request is deserialized. */
    const REQUEST_DESERIALIZED = 'zored.speech.request';
}