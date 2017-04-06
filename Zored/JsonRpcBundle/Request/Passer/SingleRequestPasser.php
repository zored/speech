<?php


namespace Zored\JsonRpcBundle\Request\Passer;


use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zored\JsonRpcBundle\Event\EventNameInterface;
use Zored\JsonRpcBundle\Event\RequestEvent;
use Zored\JsonRpcBundle\Event\ResponseEvent;
use Zored\JsonRpcBundle\Request\Entity\Request;
use Zored\JsonRpcBundle\Request\Handler\RequestHandlerInterface;
use Zored\JsonRpcBundle\Response\Entity\AbstractResponse;
use Zored\JsonRpcBundle\Response\Entity\ErrorResponse;
use Zored\JsonRpcBundle\Response\ErrorCodeInterface;
use Zored\JsonRpcBundle\Response\ErrorException;

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
    )
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->requestHandler = $requestHandler;
        $this->requestClass = $requestClass;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function suitsArray(array $data)
    {
        return isset($data['jsonrpc']);
    }

    /**
     * {@inheritDoc}
     */
    public function pass($json, array $context = [])
    {
        try {
            $request = $this->getRequest($json);
            $this->dispatchRequestReady($request);
            $response = $this->getResponse($request, $context);
            $this->dispatchError($response);
            $json = $this->serializeResponse($response);
        } catch (ErrorException $exception) {
            $response = $exception->getErrorResponse();
            $this->dispatchError($response);
            return $this->serializeResponse($response);
        }

        return $json;
    }

    /**
     * @param $json
     * @return Request
     */
    protected function getRequest($json)
    {
        $request = $this->serializer->deserialize($json, $this->requestClass, 'json');
        if (!$request instanceof Request) {
            throw new \RuntimeException("Deserialization exception.");
        }
        $this->validateRequest($request);


        return $request;
    }

    /**
     * @param Request $request
     * @param array $context
     * @return AbstractResponse
     */
    protected function getResponse(Request $request, array $context = [])
    {
        $response = $this->requestHandler->handle($request, $context);
        $this->validateResponse($response);
        return $response;
    }

    /**
     * @param $response
     * @return string
     */
    protected function serializeResponse($response): string
    {
        return $this->serializer->serialize($response, 'json');
    }

    /**
     * @param $response
     */
    protected function validateResponse($response): void
    {
        if (!$this->validator) {
            return;
        }
        $errors = $this->validator->validate($response);
        if (!count($errors)) {
            return;
        }

        throw (new ErrorException(
            "Invalid response.",
            ErrorCodeInterface::INVALID_REQUEST
        ))->setValidationErrors($errors);
    }

    /**
     * @param $request
     */
    protected function validateRequest($request): void
    {
        if (!$this->validator) {
            return;
        }
        
        $errors = $this->validator->validate($request);
        if (!count($errors)) {
            return;
        }
        
        throw (new ErrorException(
            "Invalid request.",
            ErrorCodeInterface::INVALID_REQUEST
        ))->setValidationErrors($errors);
    }

    /**
     * @param $response
     */
    protected function dispatchError($response): void
    {
        if ($this->eventDispatcher && $response instanceof ErrorResponse) {
            $this->eventDispatcher->dispatch(EventNameInterface::ERROR_RESPONSE, new ResponseEvent($response));
        }
    }

    /**
     * @param $request
     */
    protected function dispatchRequestReady($request): void
    {
        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(
                EventNameInterface::REQUEST_DESERIALIZED,
                new RequestEvent($request)
            );
        }
    }

}