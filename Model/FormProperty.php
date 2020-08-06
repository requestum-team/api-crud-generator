<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class FormProperty
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class FormProperty
{
    /**
     * @var string
     */
    private string $nameCamelCase;

    /**
     * @var string
     */
    private string $nameSnakeCase;

    /**
     * @var string
     */
    private ?string $type = null;

    /**
     * @var string
     */
    private ?string $format = null;

    /**
     * @var string
     */
    private ?string $description = null;

    /**
     * @var bool
     */
    private bool $required = false;

    /**
     * @return string
     */
    public function getNameCamelCase(): string
    {
        return $this->nameCamelCase;
    }

    /**
     * @param string $nameCamelCase
     *
     * @return FormProperty
     */
    public function setNameCamelCase(string $nameCamelCase)
    {
        $this->nameCamelCase = $nameCamelCase;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSnakeCase(): string
    {
        return $this->nameSnakeCase;
    }

    /**
     * @param string $nameSnakeCase
     *
     * @return FormProperty
     */
    public function setNameSnakeCase(string $nameSnakeCase)
    {
        $this->nameSnakeCase = $nameSnakeCase;

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
     * @return FormProperty
     */
    public function setType(?string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return FormProperty
     */
    public function setFormat(?string $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return FormProperty
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return FormProperty
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;

        return $this;
    }
}
