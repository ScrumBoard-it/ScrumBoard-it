<?php

namespace CanalTP\ScrumBoardItBundle\Service;

use CanalTP\ScrumBoardItBundle\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of ServiceFactory.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class ServiceFactory extends FactoryInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get($name)
    {
        $template = 'canal_tp_scrum_board_it.%s.service';

        return $this->container->get(sprintf($template, $name));
    }
}
