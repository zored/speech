<?php

namespace Zored\SpeechBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Validation;
use Zored\SpeechBundle\Event\EventNameInterface;
use Zored\SpeechBundle\Event\RequestEvent;
use Zored\SpeechBundle\Exception\ErrorException;
use Zored\SpeechBundle\Response\ErrorCodeInterface;

/**
 * Parses JSON-RPC request.
 */
class UUID4Subscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [EventNameInterface::REQUEST_DESERIALIZED => [['validateId', 10]]];
    }

    /**
     * @param RequestEvent $event
     */
    public function validateId(RequestEvent $event)
    {
        $violations = Validation::createValidator()->validate($event->getRequest()->getId(), [new Uuid()]);
        if (!count($violations)) {
            return;
        }

        throw new ErrorException(
            'Invalid params.',
            ErrorCodeInterface::INVALID_PARAMS,
            ['id' => $violations->get(0)->getMessage()]
        );
    }
}
