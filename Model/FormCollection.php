<?php

namespace Requestum\ApiGeneratorBundle\Model;

class FormCollection extends BaseAbstractCollection
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

    public function dump(): array
    {
        return $this->getElements();
    }
}
