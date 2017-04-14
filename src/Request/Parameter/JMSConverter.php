<?php

namespace Zored\SpeechBundle\Request\Parameter;

use JMS\Serializer\SerializerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zored\SpeechBundle\Exception\ErrorException;
use Zored\SpeechBundle\Request\Entity\Parameter;
use Zored\SpeechBundle\Response\ErrorCodeInterface;
use Zored\SpeechBundle\ZoredSpeechBundle;

class JMSConverter implements ParameterConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    public function __construct(SerializerInterface $serializer, CacheItemPoolInterface $cache = null, ValidatorInterface $validator = null)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function convert($object, $method, array $requestParameters = null)
    {
        $values = [];

        $parameters = $this->getParameters($object, $method);
        if (count($parameters) < count($requestParameters)) {
            throw new ErrorException(
                'Too much parameters.',
                ErrorCodeInterface::INVALID_PARAMS
            );
        }

        foreach ($parameters as $index => $parameter) {
            $name = $parameter->getName();

            $isAssocValue = isset($requestParameters[$name]);
            $requestHasValue = $isAssocValue || isset($requestParameters[$index]);

            // Add requested value:
            if ($requestHasValue) {
                $value = $requestParameters[$isAssocValue ? $name : $index];
                $values[] = $this->parseValue($parameter, $value);
                continue;
            }

            // No value but required:
            if ($parameter->isRequired()) {
                throw new ErrorException(
                    "Parameter '$name' is required.",
                    ErrorCodeInterface::INVALID_PARAMS
                );
            }

            // Add default value:
            $values[] = $parameter->getDefault();
        }

        return $values;
    }

    /**
     * @param Parameter $parameter
     * @param mixed     $value
     *
     * @return mixed
     */
    private function parseValue(Parameter $parameter, $value)
    {
        $class = $parameter->getClass();
        if ($class == null) {
            return $value;
        }

        $value = $this->serializer->deserialize(json_encode($value), $class, 'json');

        if (!$this->validator) {
            return $value;
        }
        $errors = $this->validator->validate($value);
        if (!count($errors)) {
            return $value;
        }

        throw (new ErrorException(
            'Parameters validation failed.',
                ErrorCodeInterface::INVALID_PARAMS
        ))->setValidationErrors($errors);
    }

    /**
     * @param $object
     * @param string $method
     *
     * @return Parameter[]
     */
    private function getParameters($object, $method)
    {
        $serviceName = get_class($object);

        if ($this->cache) {
            // Get cached item:
            $cached = $this->cache->getItem(ZoredSpeechBundle::PREFIX . ".$serviceName:$method.parameters");
            if ($cached->isHit()) {
                return $cached->get();
            }
        }

        // Generate parameters:
        $parameters = [];
        foreach ((new \ReflectionMethod($object, $method))->getParameters() as $parameter) {
            $class = $parameter->getClass();
            $class = $class == null ? null : $class->getName();

            $parameters[] = (new Parameter())
                ->setName($parameter->getName())
                ->setClass($class)
                ->setDefault($parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null)
                ->setRequired(!$parameter->isOptional());
        }

        if (isset($cached)) {
            // Save cache:
            $cached->set($parameters);
            $this->cache->save($cached);
        }

        return $parameters;
    }
}
