<?php


namespace Zored\JsonRpcBundle\Response;

use Zored\JsonRpcBundle\Response\Entity\Error;
use Zored\JsonRpcBundle\Response\Entity\AbstractResponse;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Zored\JsonRpcBundle\Response\Entity\ErrorResponse;

/**
 * Enum of error codes.
 */
class ErrorException extends \RuntimeException
{
    private $data;

    public function __construct($message, $code, $data = null)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getErrorResponse()
    {
        return (new ErrorResponse())
            ->setError(
                (new Error())
                    ->setCode($this->code)
                    ->setMessage($this->getMessage())
                    ->setData($this->data)
            );
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return $this
     */
    public function setValidationErrors(ConstraintViolationListInterface $errors)
    {
        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $this->data[$error->getPropertyPath()] = $error->getMessage();
        }
        return $this;
    }
}