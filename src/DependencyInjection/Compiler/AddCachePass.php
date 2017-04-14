<?php


namespace Zored\SpeechBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Add Symfony 3.1 cache service.
 */
class AddCachePass implements CompilerPassInterface
{
    protected $services = [
        [
            'zored.speech.request.reflection_method_checker',
            'zored.speech.request.reflection_method_checker.cache_service',
            1
        ],
        [
            'zored.speech.request.parameter_converter',
            'zored.speech.request.parameter_converter.cache_service',
            1
        ],
        [
            'zored.speech.request.passer.single',
            'zored.speech.request.parameter_converter.event_dispatcher_service',
            4
        ]
    ];

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->services as $service) {
            list($serviceName, $parameterName, $index) = $service;
            $argumentServiceName = $container->getParameter($parameterName);
            if (!$container->hasDefinition($argumentServiceName)) {
                continue;
            }
            $argument = $container->getDefinition($argumentServiceName);
            $container
                ->getDefinition($serviceName)
                ->replaceArgument($index, $argument);
        }
    }
}