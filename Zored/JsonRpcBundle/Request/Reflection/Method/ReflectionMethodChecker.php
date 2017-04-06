<?php


namespace Zored\JsonRpcBundle\Request\Reflection\Method;


use Zored\JsonRpcBundle\ZoredJsonRpcBundle;
use Zored\JsonRpcBundle\Request\Reflection\Parameter\ParameterConverterInterface;
use Psr\Cache\CacheItemPoolInterface;

class ReflectionMethodChecker implements MethodAccessCheckerInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var ParameterConverterInterface
     */
    private $parameterConverter;

    /**
     * @param CacheItemPoolInterface $cache
     * @param ParameterConverterInterface $parameterConverter
     */
    public function __construct(ParameterConverterInterface $parameterConverter, CacheItemPoolInterface $cache = null)
    {
        $this->cache = $cache;
        $this->parameterConverter = $parameterConverter;
    }


    /**
     * @inheritDoc
     */
    public function isAvailable($object, $method)
    {
        return $this->isPublic($object, $method);
    }

    /**
     * @param $object
     * @param $method
     * @return bool
     */
    protected function isPublic($object, $method)
    {
        $serviceName = get_class($object);

        if ($this->cache) {
            // Get cached item:
            $cached = $this->cache->getItem(ZoredJsonRpcBundle::PREFIX . ".$serviceName:$method.isPublic");
            if ($cached->isHit()) {
                return (bool)$cached->get();
            }
        }

        $methodReflection = new \ReflectionMethod($object, $method);

        $isPublic = $methodReflection->isPublic() && $methodReflection->getName()[0] != '_';

        if (isset($cached)) {
            // Save cache:
            $cached->set($isPublic);
            $this->cache->save($cached);
        }


        return $isPublic;
    }

}