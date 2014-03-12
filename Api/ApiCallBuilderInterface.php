<?php

namespace CanalTP\ScrumBoardItBundle\Api;

/**
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
interface ApiCallBuilderInterface {
    public function __construct(array $options = array());
    public function setApiConfiguration(ApiCallConfigurationInterface $apiConfiguration);
    public function getOptions();
    public function setOptions(array $options);
    public function getResult();
    public function setResult($result);
    public function call();
}
