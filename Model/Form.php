<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class Form
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class Form implements ModelInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private ?string $description = null;

    /**
     * @var string
     */
    private string $nameSpace = 'Form';

    /**
     * @var Entity
     */
    private ?Entity $entity = null;

    /**
     * @var FormProperty[]
     */
    private array $properties = [];

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
     * @return Form
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Form
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return implode('/', [...explode('\\', $this->getNameSpace()), implode('.', [$this->name, 'php'])]);
    }

    /**
     * @return string
     */
    public function getNameSpace(): string
    {
        return $this->nameSpace;
    }

    /**
     * @param string $nameSpace
     *
     * @return Form
     */
    public function setNameSpace(string $nameSpace)
    {
        $this->nameSpace = $nameSpace;

        return $this;
    }

    /**
     * @return Entity|null
     */
    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity|null $entity
     *
     * @return Form
     */
    public function setEntity(?Entity $entity)
    {
        $this->entity = $entity;

        if (!is_null($entity)) {
            $this->nameSpace = implode('\\', [$this->nameSpace, $entity->getName()]);
        }

        return $this;
    }

    /**
     * @return FormProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param FormProperty[] $properties
     *
     * @return Form
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return FormProperty[]
     */
    public function filterReferencedLinkProperties(): array
    {
        return array_filter($this->getProperties(), function (FormProperty $el) {
            return !is_null($el->getReferencedLink());
        });
    }

    /**
     * @param string $name
     *
     * @return FormProperty|null
     */
    public function getPropertyByNameCamelCase(string $name): ?FormProperty
    {
        $result = array_filter($this->getProperties(), function (FormProperty $el) use ($name) {
            return $el->getNameCamelCase() === $name;
        });

        return count($result) === 1 ? array_shift($result): null;
    }
}
