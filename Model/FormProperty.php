<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class FormProperty
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class FormProperty extends BaseAbstractProperty
{
    /**
     * @var string
     */
    protected string $nameCamelCase;

    /**
     * @var string
     */
    protected string $nameSnakeCase;

    /**
     * @var boolean
     */
    protected ?bool $isEntity = false;

    /**
     * @var boolean
     */
    protected ?bool $isForm = false;

    /**
     * @var Entity|null
     */
    protected ?Entity $entity = null;

    /**
     * @var Form|null
     */
    protected ?Form $form = null;

    /** @var LengthProperty */
    protected LengthProperty $length;

    /**
     * FormProperty constructor.
     */
    public function __construct()
    {
        $this->length = new LengthProperty();
    }

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
     * @return bool
     */
    public function isEntity(): bool
    {
        return $this->isEntity;
    }

    /**
     * @param bool $isEntity
     *
     * @return FormProperty
     */
    public function setIsEntity(bool $isEntity)
    {
        $this->isEntity = $isEntity;

        return $this;
    }

    /**
     * @return bool
     */
    public function isForm(): bool
    {
        return $this->isForm;
    }

    /**
     * @param bool $isForm
     *
     * @return FormProperty
     */
    public function setIsForm(bool $isForm)
    {
        $this->isForm = $isForm;

        return $this;
    }

    /**
     * @param Form|Entity $object
     *
     * @return FormProperty
     */
    public function setReferencedObject($object)
    {
        if ($this->isEntity()) {
            $this->entity = $object;
        }

        if ($this->isForm()) {
            $this->form = $object;
        }

        return $this;
    }

    /**
     * @return Form|Entity|null
     */
    public function getReferencedObject()
    {
        if ($this->isEntity()) {
            return $this->entity;
        }

        if ($this->isForm()) {
            return $this->form;
        }

        return null;
    }

    /**
     * @return LengthProperty
     */
    public function getLength(): LengthProperty
    {
        return $this->length;
    }

    /**
     * @param LengthProperty $length
     *
     * @return $this
     */
    public function setLength(LengthProperty $length): self
    {
        $this->length = $length;

        return $this;
    }
}
