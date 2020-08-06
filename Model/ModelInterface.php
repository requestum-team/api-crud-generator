<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Interface ModelInterface
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
interface ModelInterface
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getName(): string;
}
