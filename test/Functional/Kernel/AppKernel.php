<?php

namespace Zored\SpeechBundle\Test\Functional\Kernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Test kernel.
 */
class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);
        $this->rootDir = __DIR__;

        // Remove cache directory:
        $dir = $this->getDir();
        if (is_dir($dir)) {
            $dir = escapeshellarg($dir);
            system("rm -rf $dir");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new \Zored\SpeechBundle\ZoredSpeechBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $dir = __DIR__ . '/../Resources/config';
        $loader->load($dir . '/config.yml');
        $loader->load($dir . '/services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->getDir() . '/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->getDir() . '/log';
    }

    private function getDir()
    {
        $dir = __DIR__ . '/tmp';

        return $dir;
    }
}
