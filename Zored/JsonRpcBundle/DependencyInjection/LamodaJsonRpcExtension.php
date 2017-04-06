<?php


namespace Zored\JsonRpcBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Don't move.
 * @link http://symfony.com/doc/current/bundles/extension.html
 */
class LamodaJsonRpcExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load services:
        (new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        ))->load('services.yml');
    }
}
