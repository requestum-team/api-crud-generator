<?php

namespace Requestum\ApiGeneratorBundle\Model;

use Requestum\ApiGeneratorBundle\Service\Config;

abstract class BaseAbstractCollection
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param ModelInterface $element
     *
     * @return $this
     */
    public function addElement(ModelInterface $element)
    {
        $this->elements[$element->getName()][] = $element;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->elements) === 0;
    }

    /**
     * @return ModelInterface
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * @param string $name
     *
     * @return Form|Entity|null
     */
    public function findElement(string $name)
    {
        if (isset($this->elements[$name])) {
            return $this->elements[$name];
        }

        return null;
    }

    abstract public function dump(): array;
}
