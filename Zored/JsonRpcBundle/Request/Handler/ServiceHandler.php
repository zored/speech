<?php


namespace Zored\JsonRpcBundle\Request\Handler;


use Zored\JsonRpcBundle\Request\Entity\Request;
use Zored\JsonRpcBundle\Request\Reflection\Method\MethodAccessCheckerInterface;
use Zored\JsonRpcBundle\Request\Reflection\Parameter\ParameterConverterInterface;
use Zored\JsonRpcBundle\Response\Entity\Error;
use Zored\JsonRpcBundle\Response\Entity\AbstractResponse;
use Zored\JsonRpcBundle\Response\Entity\ErrorResponse;
use Zored\JsonRpcBundle\Response\Entity\SuccessResponse;
use Zored\JsonRpcBundle\Response\ErrorCodeInterface as ErrorCode;
use Zored\JsonRpcBundle\Response\ErrorException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Apply JSON-RPC request to service and get JSON-RPC response.
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
     * @param ContainerInterface $container
     * @param MethodAccessCheckerInterface $methodAccessChecker
     * @param ParameterConverterInterface $parameterConverter
     */
    public function __construct(ContainerInterface $container, MethodAccessCheckerInterface $methodAccessChecker, ParameterConverterInterface $parameterConverter)
    {
        $this->container = $container;
        $this->parameterConverter = $parameterConverter;
        $this->methodAccessChecker = $methodAccessChecker;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, array $context = [])
    {
        list($name, $method) = explode(':', $request->getMethod());

        if (empty($context['serviceNames'])) {
            throw new \UnexpectedValueException('Limit your services with $context["serviceNames"].');
        }

        if (!in_array($name, $context['serviceNames'])) {
            return $this->getErrorResponse(
                ErrorCode::METHOD_NOT_FOUND,
                "Service '$name' doesn't exist.",
                $request->getId()
            );
        }

        $service = $this->container->get($name);

        if (!$this->methodAccessChecker->isAvailable($service, $method)) {
            return $this->getErrorResponse(
                ErrorCode::METHOD_NOT_FOUND,
                "Method '$method' is not avaliable in '$name'.",
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
            $response = call_user_func_array([$service, $method], $values);

            if (is_array($response)) {
                return $this->getSuccessResponse($response, $request->getId());
            }

            if ($response instanceof AbstractResponse) {
                return $response;
            }

            throw new \RuntimeException("Unknown response type from $name:$method().");
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
     * @return AbstractResponse
     */
    private function getSuccessResponse($data, $id)
    {
        return (new SuccessResponse())
            ->setResult($data)
            ->setId($id);
    }
}