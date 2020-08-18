<?php

namespace Requestum\ApiGeneratorBundle\Model\Generator;

/**
 * Class GeneratorParameterModel
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class GeneratorParameterModel
{

    /**
     * @var string
     */
    protected ?string $name = null;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var int
     */
    protected int $position = 0;

    /**
     * @var string
     */
    protected ?string $defaultValue = null;

    /**
     * @var bool
     */
    protected bool $isReturn = false;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return GeneratorParameterModel
     */
    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return GeneratorParameterModel
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return GeneratorParameterModel
     */
    public function setPosition(int $position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    /**
     * @param string|null $defaultValue
     *
     * @return GeneratorParameterModel
     */
    public function setDefaultValue(?string $defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReturn(): bool
    {
        return $this->isReturn;
    }

    /**
     * @param bool $isReturn
     *
     * @return GeneratorParameterModel
     */
    public function setIsReturn(bool $isReturn)
    {
        $this->isReturn = $isReturn;

        return $this;
    }
}
