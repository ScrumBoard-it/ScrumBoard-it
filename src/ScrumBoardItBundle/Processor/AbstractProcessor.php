<?php

namespace ScrumBoardItBundle\Processor;

use ScrumBoardItBundle\Api\ApiCallBuilderInterface;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    private $context;

    public function getContext()
    {
        return $this->context;
    }

    public function setContext(ApiCallBuilderInterface $context)
    {
        $this->context = $context;

        return $this;
    }
}
