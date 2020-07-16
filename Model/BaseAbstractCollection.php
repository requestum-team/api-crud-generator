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
     * @var Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getElements(): array {
        return $this->elements;
    }

    /**
     * @param Action $action
     *
     * @return $this
     */
    public function addElement($element) {
        $this->elements[$element->getName()][] = $element;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return count($this->elements) === 0;
    }

    abstract public function dump(): array;
}
