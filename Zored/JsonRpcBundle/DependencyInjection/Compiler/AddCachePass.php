<?php


namespace Zored\JsonRpcBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Add Symfony 3.1 cache service.
 */
class AddCachePass implements CompilerPassInterface
{
    protected $services = [
        [
            'lamoda.json_rpc.request.reflection_method_checker',
            'lamoda.json_rpc.request.reflection_method_checker.cache_service',
            1
        ],
        [
            'lamoda.json_rpc.request.parameter_converter',
            'lamoda.json_rpc.request.parameter_converter.cache_service',
            1
        ],
        [
            'lamoda.json_rpc.request.passer.single',
            'lamoda.json_rpc.request.parameter_converter.event_dispatcher_service',
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