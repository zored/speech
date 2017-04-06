<?php

namespace Zored\JsonRpcBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zored\JsonRpcBundle\DependencyInjection\Compiler\AddCachePass;
use Zored\JsonRpcBundle\DependencyInjection\LamodaJsonRpcExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZoredJsonRpcBundle extends Bundle
{
    const PREFIX = 'lamoda.json_rpc';

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new LamodaJsonRpcExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddCachePass());
    }
}
