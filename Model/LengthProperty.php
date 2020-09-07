<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class LengthProperty
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class LengthProperty
{
    /** @var int|null */
    protected ?int $min = null;

    /** @var int|null */
    protected ?int $max = null;

    /**
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @param int|null $min
     *
     * @return $this
     */
    public function setMin(?int $min): self
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     *
     * @return $this
     */
    public function setMax(?int $max): self
    {
        $this->max = $max;

        return $this;
    }
}
