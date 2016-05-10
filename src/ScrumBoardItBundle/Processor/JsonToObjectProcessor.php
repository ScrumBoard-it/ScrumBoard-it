<?php

namespace ScrumBoardItBundle\Processor;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class JsonToObjectProcessor extends AbstractProcessor
{
    public function handle()
    {
        $data = $this->getContext()->getResult();
        $this->getContext()->setResult(json_decode($data));
    }
}
