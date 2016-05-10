<?php

namespace CanalTP\ScrumBoardItBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of ServiceManager.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class ServiceManager
{
    private $serviceName;
    private $serviceFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->setServiceFactory(new ServiceFactory($container));
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function setServiceName($serviceName)
    {
        $this->serviceName = ucfirst(strtolower($serviceName));

        return $this;
    }

    public function getServiceFactory()
    {
        return $this->serviceFactory;
    }

    public function setServiceFactory($serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;

        return $this;
    }

    public function getService()
    {
        $factory = $this->getServiceFactory();

        return $factory->get($this->serviceName);
    }
}
