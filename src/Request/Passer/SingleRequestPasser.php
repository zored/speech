<?php

namespace Zored\SpeechBundle\Request\Passer;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zored\SpeechBundle\Endpoint\Context\ContextInterface;
use Zored\SpeechBundle\Event\EventNameInterface;
use Zored\SpeechBundle\Event\RequestEvent;
use Zored\SpeechBundle\Event\ResponseEvent;
use Zored\SpeechBundle\Exception\ErrorException;
use Zored\SpeechBundle\Request\Entity\Request;
use Zored\SpeechBundle\Request\Handler\RequestHandlerInterface;
use Zored\SpeechBundle\Response\Entity\AbstractResponse;
use Zored\SpeechBundle\Response\Entity\ErrorResponse;
use Zored\SpeechBundle\Response\ErrorCodeInterface;

/**
 * Passes single request array to handler.
 */
class SingleRequestPasser implements RequestPasserInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @var string
     */
    private $requestClass;

    /**
     * @var EventDispatcherInterface|null
     */
    private $eventDispatcher;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        RequestHandlerInterface $requestHandler,
        $requestClass,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->requestHandler = $requestHandler;
        $this->requestClass = $requestClass;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function suitsArray(array $data)
    {
        return isset($data['jsonrpc']);
    }

    /**
     * {@inheritdoc}
     */
    public function pass($requestContent, ContextInterface $context = null)
    {
        try {
            $request = $this->getRequest($requestContent);
            $this->dispatchRequestReady($request);
            $response = $this->getResponse($request, $context);
            $this->dispatchError($response);
            $responseContent = $this->serializeResponse($response);
        } catch (ErrorException $exception) {
            $response = $exception->getErrorResponse();
            $this->dispatchError($response);

            return $this->serializeResponse($response);
        }

        return $responseContent;
    }

    /**
     * @param $json
     *
     * @return Request
     */
    protected function getRequest($json)
    {
        $request = $this->serializer->deserialize($json, $this->requestClass, 'json');
        if (!$request instanceof Request) {
            throw new \RuntimeException('Deserialization exception.');
        }
        $this->validateRequest($request);

        return $request;
    }

    /**
     * @param Request          $request
     * @param ContextInterface $context
     *
     * @return AbstractResponse
     */
    protected function getResponse(Request $request, ContextInterface $context = null)
    {
        $response = $this->requestHandler->handle($request, $context);
        $this->validateResponse($response);

        return $response;
    }

    /**
     * @param $response
     *
     * @return string
     */
    protected function serializeResponse($response)
    {
        return $this->serializer->serialize($response, 'json');
    }

    /**
     * @param $response
     */
    protected function validateResponse($response)
    {
        if (!$this->validator) {
            return;
        }
        $errors = $this->validator->validate($response);
        if (!count($errors)) {
            return;
        }

        throw (new ErrorException(
            'Invalid response.',
            ErrorCodeInterface::INVALID_REQUEST
        ))->setValidationErrors($errors);
    }

    /**
     * @param $request
     */
    protected function validateRequest($request)
    {
        if (!$this->validator) {
            return;
        }

        $errors = $this->validator->validate($request);
        if (!count($errors)) {
            return;
        }

        throw (new ErrorException(
            'Invalid request.',
            ErrorCodeInterface::INVALID_REQUEST
        ))->setValidationErrors($errors);
    }

    /**
     * @param $response
     */
    protected function dispatchError($response)
    {
        if ($this->eventDispatcher && $response instanceof ErrorResponse) {
            $this->eventDispatcher->dispatch(EventNameInterface::ERROR_RESPONSE, new ResponseEvent($response));
        }
    }

    /**
     * @param $request
     */
    protected function dispatchRequestReady($request)
    {
        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(
                EventNameInterface::REQUEST_DESERIALIZED,
                new RequestEvent($request)
            );
        }
    }
}
