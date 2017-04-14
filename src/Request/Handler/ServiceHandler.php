<?php

namespace Zored\SpeechBundle\Request\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Zored\SpeechBundle\Endpoint\Context\ContextInterface;
use Zored\SpeechBundle\Endpoint\Context\ServiceContextInterface;
use Zored\SpeechBundle\Exception\ErrorException;
use Zored\SpeechBundle\Request\Entity\Request;
use Zored\SpeechBundle\Request\Method\MethodAccessCheckerInterface;
use Zored\SpeechBundle\Request\Parameter\ParameterConverterInterface;
use Zored\SpeechBundle\Response\Entity\AbstractResponse;
use Zored\SpeechBundle\Response\Entity\Error;
use Zored\SpeechBundle\Response\Entity\ErrorResponse;
use Zored\SpeechBundle\Response\Entity\SuccessResponse;
use Zored\SpeechBundle\Response\ErrorCodeInterface as ErrorCode;

/**
 * Apply JSON-RPC request to service and get JSON-RPC response.
 *
 * @see Request
 * @see AbstractResponse
 */
class ServiceHandler implements RequestHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ParameterConverterInterface
     */
    private $parameterConverter;

    /**
     * @var MethodAccessCheckerInterface
     */
    private $methodAccessChecker;

    /**
     * @param ContainerInterface           $container
     * @param MethodAccessCheckerInterface $methodAccessChecker
     * @param ParameterConverterInterface  $parameterConverter
     */
    public function __construct(ContainerInterface $container, MethodAccessCheckerInterface $methodAccessChecker, ParameterConverterInterface $parameterConverter)
    {
        $this->container = $container;
        $this->parameterConverter = $parameterConverter;
        $this->methodAccessChecker = $methodAccessChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, ContextInterface $context = null)
    {
        list($id, $method) = explode(':', $request->getMethod());

        if (!$context instanceof ServiceContextInterface) {
            throw new \LogicException('Wrong context has been passed.');
        }

        if (!$context->hasServiceId($id)) {
            return $this->getErrorResponse(
                ErrorCode::METHOD_NOT_FOUND,
                "Service '$id' doesn't exist.",
                $request->getId()
            );
        }

        $service = $this->container->get($id);

        if (!$this->methodAccessChecker->isAvailable($service, $method)) {
            return $this->getErrorResponse(
                ErrorCode::METHOD_NOT_FOUND,
                "Method '$method' is not avaliable in '$id'.",
                $request->getId()
            );
        }

        // Get method parameters and return error response on failure:
        try {
            $values = $this->parameterConverter->convert($service, $method, $request->getParams());
        } catch (ErrorException $exception) {
            return $exception->getErrorResponse()->setId($request->getId());
        }

        // Get service response and return internal error on failure:
        try {
            try {
                $response = call_user_func_array([$service, $method], $values);
            } catch (\InvalidArgumentException $exception) {
                return $this->getErrorResponse(ErrorCode::INTERNAL_ERROR, "Error calling method '$method'.");
            }

            if (is_array($response)) {
                return $this->getSuccessResponse($response, $request->getId());
            }

            if ($response instanceof AbstractResponse) {
                return $response;
            }

            throw new \RuntimeException("Unknown response type from $id:$method().");
        } catch (\Exception $exception) {
            return $this->getErrorResponse(
                ErrorCode::INTERNAL_ERROR,
                $exception->getMessage(),
                $request->getId()
            );
        }

        // Done!
    }

    private function getErrorResponse($code, $message, $id = null)
    {
        $error = (new Error())
            ->setCode($code)
            ->setMessage($message);

        return (new ErrorResponse())
            ->setError($error)
            ->setId($id);
    }

    /**
     * @param $data
     * @param $id
     *
     * @return AbstractResponse
     */
    private function getSuccessResponse($data, $id)
    {
        return (new SuccessResponse())
            ->setResult($data)
            ->setId($id);
    }
}
