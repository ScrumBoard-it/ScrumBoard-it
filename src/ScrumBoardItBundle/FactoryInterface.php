<?php
namespace ScrumBoardItBundle;

use ScrumBoardItBundle\Exception\ClassNotFoundException;

/**
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
abstract class FactoryInterface
{
    protected $suffix;

    public function __construct($suffix = null)
    {
        if (! is_null($suffix)) {
            $this->setSuffix($suffix);
        }
    }

    public function getSuffix()
    {
        return $this->suffix;
    }

    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
        
        return $this;
    }

    public function get($name)
    {
        $className = $name . $this->getSuffix();
        $class = $this->getNamespace() . '\\' . $className;
        if (class_exists($class)) {
            $instance = new $class();
            
            return $instance;
        } else {
            throw new ClassNotFoundException($class);
        }
    }

    public function getNamespace()
    {
        $factoryClass = get_class($this);
        $folder = explode('\\', $factoryClass);
        array_pop($folder);
        
        return implode('\\', $folder);
    }
}
