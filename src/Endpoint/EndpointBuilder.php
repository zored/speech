<?php

namespace Zored\SpeechBundle\Endpoint;

use Doctrine\Common\Annotations\AnnotationReader;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zored\SpeechBundle\Request\Entity\Request;
use Zored\SpeechBundle\Request\Handler\RequestHandlerInterface;
use Zored\SpeechBundle\Request\Handler\ServiceHandler;
use Zored\SpeechBundle\Request\Method\IsCallableChecker;
use Zored\SpeechBundle\Request\Method\MethodAccessCheckerInterface;
use Zored\SpeechBundle\Request\Parameter\JMSConverter;
use Zored\SpeechBundle\Request\Passer\BatchRequestPasser;
use Zored\SpeechBundle\Request\Passer\RequestPasserInterface;
use Zored\SpeechBundle\Request\Passer\SingleRequestPasser;

/**
 * Factory for.
 *
 * @see \Zored\SpeechBundle\Endpoint\Endpoint
 */
class EndpointBuilder
{
    /**
     * @var string[]
     */
    protected $services = [];

    /**
     * @var EventSubscriberInterface[]
     */
    protected $subscribers = [];

    /**
     * @param $name
     * @param $class
     *
     * @return $this
     */
    public function addService($name, $class)
    {
        $this->services[$name] = $class;

        return $this;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     *
     * @return $this
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->subscribers[] = $subscriber;

        return $this;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint()
    {
        return new Endpoint($this->createPassers());
    }

    /**
     * @return MethodAccessCheckerInterface
     */
    protected function createMethodAccessChecker()
    {
        return new IsCallableChecker();
    }

    /**
     * @return JMSConverter
     */
    protected function createParameterConverter()
    {
        return new JMSConverter($this->createSerializer(), null, $this->createValidator());
    }

    /**
     * @return RequestPasserInterface[]
     */
    protected function createPassers()
    {
        $single = $this->createSingleRequestPasser();
        $batch = $this->createBatchRequestPasser($single);

        return [$single, $batch];
    }

    /**
     * @return ContainerInterface
     */
    protected function createContainer()
    {
        $container = new ContainerBuilder();
        foreach ($this->services as $name => $class) {
            $container->register($name, $class);
        }

        return $container;
    }

    /**
     * @return SerializerInterface
     */
    protected function createSerializer()
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer;
    }

    /**
     * @return ValidatorInterface
     */
    protected function createValidator()
    {
        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping(new AnnotationReader())
            ->getValidator();
    }

    /**
     * @return RequestPasserInterface
     */
    protected function createSingleRequestPasser()
    {
        return new SingleRequestPasser(
            $this->createSerializer(),
            $this->createValidator(),
            $this->createRequestHandler(),
            Request::class,
            $this->createEventDispatcher()
        );
    }

    /**
     * @return RequestHandlerInterface
     */
    protected function createRequestHandler()
    {
        $container = $this->createContainer();
        $methodAccessChecker = $this->createMethodAccessChecker();
        $parameterConverter = $this->createParameterConverter();

        return new ServiceHandler($container, $methodAccessChecker, $parameterConverter);
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function createEventDispatcher()
    {
        $eventDispatcher = new EventDispatcher();
        foreach ($this->subscribers as $subscriber) {
            $eventDispatcher->addSubscriber($subscriber);
        }

        return $eventDispatcher;
    }

    /**
     * @param RequestPasserInterface $single
     *
     * @return BatchRequestPasser
     */
    protected function createBatchRequestPasser(RequestPasserInterface $single)
    {
        return new BatchRequestPasser($single);
    }
}
