<?php

namespace Zored\SpeechBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zored\SpeechBundle\DependencyInjection\Compiler\AddCachePass;
use Zored\SpeechBundle\DependencyInjection\ZoredSpeechExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZoredSpeechBundle extends Bundle
{
    const PREFIX = 'zored.json_rpc';

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new ZoredSpeechExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddCachePass());
    }
}
