<?php

namespace Requestum\ApiGeneratorBundle\Model\Generator;

use Requestum\ApiGeneratorBundle\Exception\AccessLevelException;

/**
 * Class GeneratorPropertyModel
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class GeneratorPropertyModel
{

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @var string
     *
     * @example public, protected, private
     */
    protected string $accessLevel;

    /**
     * @var string
     */
    protected ?string $defaultValue = null;

    /**
     * @var string
     */
    protected ?string $defaultValueType = null;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return GeneratorPropertyModel
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return GeneratorPropertyModel
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param string $attribut
     *
     * @return GeneratorPropertyModel
     */
    public function addAttribut(string $attribut)
    {
        if (!in_array($attribut, $this->attributes)) {
            $this->attributes[] = $attribut;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessLevel(): string
    {
        return $this->accessLevel;
    }

    /**
     * @param string $accessLevel
     *
     * @return GeneratorPropertyModel
     */
    public function setAccessLevel(string $accessLevel)
    {
        if (!in_array($accessLevel, AccessLevelEnum::getAccessLevels())) {
            throw new AccessLevelException(
                sprintf(
                    'Wrong access level %s. Possible access levels %s',
                    $accessLevel,
                    implode(', ', AccessLevelEnum::getAccessLevels())
                )
            );
        }
        $this->accessLevel = $accessLevel;

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
     * @return GeneratorPropertyModel
     */
    public function setDefaultValue(?string $defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultValueType(): ?string
    {
        return $this->defaultValueType;
    }

    /**
     * @param string|null $defaultValueType
     *
     * @return GeneratorPropertyModel
     */
    public function setDefaultValueType(?string $defaultValueType)
    {
        $this->defaultValueType = $defaultValueType;

        return $this;
    }
}
