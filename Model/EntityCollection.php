<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class EntityCollection
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class EntityCollection extends BaseAbstractCollection
{
    /**
     * @param ModelInterface $element
     *
     * @return $this
     */
    public function addElement(ModelInterface $element)
    {
        $this->elements[$element->getName()] = $element;

        return $this;
    }

    /**
     * @return array
     */
    public function dump(): array
    {
        return $this->getElements();
    }
}
