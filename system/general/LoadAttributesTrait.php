<?php


namespace system\general;


use ReflectionException;
use ReflectionProperty;

/**
 * Trait LoadAttributesTrait
 * @package system\general
 */
trait LoadAttributesTrait
{
    /**
     * Список свойств класса для массового присвоения
     *
     * @return array
     */
    abstract public function getAttributes(): array;

    /**
     * Массово присваивает значения
     *
     * @param object $source
     * @throws ReflectionException
     */
    public function loadAttributes(object $source): void
    {
        foreach ($this->getAttributes() as $attribute) {
            $prop = new ReflectionProperty($this, $attribute);
            $typeProp = $prop->getType();
            if ($typeProp && $typeProp->isBuiltin() && property_exists($source, $attribute) && !is_null($source->$attribute))
                $this->$attribute = $source->$attribute;
        }
    }
}