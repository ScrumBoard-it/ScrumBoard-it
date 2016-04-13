<?php

namespace CanalTP\ScrumBoardItBundle\Processor;

use CanalTP\ScrumBoardItBundle\Api\ApiCallBuilderInterface;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface ProcessorInterface
{
    public function setContext(ApiCallBuilderInterface $context);
    public function handle();
}
