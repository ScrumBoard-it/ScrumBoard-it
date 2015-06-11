<?php

namespace CanalTP\ScrumBoardItBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use CanalTP\ScrumBoardItBundle\DependencyInjection\Security\Factory\JiraFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CanalTPScrumBoardItBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new JiraFactory());
    }
}
