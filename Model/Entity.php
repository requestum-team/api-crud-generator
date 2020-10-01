<?php

namespace Requestum\ApiGeneratorBundle\Model;

use Requestum\ApiGeneratorBundle\Helper\CommonHelper;

/**
 * Class Entity
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class Entity implements ModelInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $originObjectName;

    /**
     * @var string
     */
    private string $tableName;

    /**
     * @var string
     */
    private ?string $description = null;

    /**
     * @var string
     */
    private string $nameSpace = 'Entity';

    /**
     * @var EntityProperty[]
     */
    private array $primaryColumns = [];

    /**
     * Entity's foreign key metadatas
     *
     * @var EntityProperty[]
     */
    private array $foreignKeys = [];

    /**
     * The list of columns that reference on the entity's primary key
     *
     * @var EntityProperty[]
     */
    private array $referenceKeys = [];

    /**
     * @var EntityProperty[]
     */
    private array $uniques = [];

    /**
     * @var EntityProperty[]
     */
    private array $properties = [];

    /**
     * @var array
     */
    private array $traits = [];

    /**
     * @var array
     */
    private array $repositoryTraits = [];

    /** @var array */
    private array $repositoryInterfaces = [];

    /**
     * @var array
     */
    private array $annotations = [];

    /**
     * @var array
     */
    private array $interfaces = [];

    /**
     * @var bool
     */
    private bool $generate = true;

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
     * @return Entity
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginObjectName(): string
    {
        return $this->originObjectName;
    }

    /**
     * @param string $originObjectName
     *
     * @return Entity
     */
    public function setOriginObjectName(string $originObjectName)
    {
        $this->originObjectName = $originObjectName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     *
     * @return Entity
     */
    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;

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
     * @return Entity
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSpace(): string
    {
        return $this->nameSpace . '\\' . $this->getName();
    }

    /**
     * @param string $nameSpace
     *
     * @return $this
     */
    public function setNameSpace(string $nameSpace): self
    {
        $this->nameSpace = $nameSpace;

        return $this;
    }

    /**
     * @return EntityProperty[]
     */
    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }

    /**
     * @param EntityProperty[] $foreignKeys
     *
     * @return Entity
     */
    public function setForeignKeys(array $foreignKeys)
    {
        $this->foreignKeys = $foreignKeys;

        return $this;
    }

    /**
     * @param EntityProperty $foreignKey
     *
     * @return Entity
     */
    public function addForeignKey(EntityProperty $foreignKey)
    {
        if (!in_array($foreignKey, $this->foreignKeys, true)) {
            $this->foreignKeys[] = $foreignKey;
        }

        return $this;
    }

    /**
     * @return EntityProperty[]
     */
    public function getReferenceKeys(): array
    {
        return $this->referenceKeys;
    }

    /**
     * @param EntityProperty[] $referenceKeys
     *
     * @return Entity
     */
    public function setReferenceKeys(array $referenceKeys)
    {
        $this->referenceKeys = $referenceKeys;

        return $this;
    }

    /**
     * @return EntityProperty[]
     */
    public function getPrimaryColumns(): array
    {
        return $this->primaryColumns;
    }

    /**
     * @param EntityProperty[] $primaryColumns
     *
     * @return Entity
     */
    public function setPrimaryColumns(array $primaryColumns)
    {
        $this->primaryColumns = $primaryColumns;

        return $this;
    }

    /**
     * @param EntityProperty $primaryColumn
     *
     * @return Entity
     */
    public function addPrimaryColumn(EntityProperty $primaryColumn)
    {
        if (!in_array($primaryColumn, $this->primaryColumns, true)) {
            $this->primaryColumns[] = $primaryColumn;
        }

        return $this;
    }

    /**
     * @return EntityProperty[]
     */
    public function getUniques(): array
    {
        return $this->uniques;
    }

    /**
     * @param EntityProperty[] $uniques
     *
     * @return Entity
     */
    public function setUniques(array $uniques)
    {
        $this->uniques = $uniques;

        return $this;
    }

    /**
     * @param EntityProperty $unique
     *
     * @return Entity
     */
    public function addUnique(EntityProperty $unique)
    {
        if (!in_array($unique, $this->uniques, true)) {
            $this->uniques[] = $unique;
        }

        return $this;
    }

    /**
     * @return EntityProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param EntityProperty[] $properties
     *
     * @return Entity
     */
    public function setProperties(array $properties)
    {
        /** @var EntityProperty $property */
        foreach ($properties as $property) {
            $property->setEntity($this);

            if ($property->isPrimary()) {
                $this->addPrimaryColumn($property);
            }
        }

        $this->properties = $properties;

        return $this;
    }

    /**
     * @param EntityProperty $property
     *
     * @return Entity
     */
    public function addProperty(EntityProperty $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @return EntityProperty[]
     */
    public function filterReferencedEntityLinkProperties(): array
    {
        return array_filter($this->getProperties(), function (EntityProperty $el) {
            return !is_null($el->getReferencedLink()) && CommonHelper::isEntity($el->getReferencedLink());
        });
    }

    /**
     * @param string $entityName
     *
     * @return EntityProperty[]|null
     */
    public function getRelatedProperty(string $entityName): ?EntityProperty
    {
        $referencedLinkProperties = $this->filterReferencedEntityLinkProperties();
        if (count($referencedLinkProperties) > 0) {
            $result = array_filter($referencedLinkProperties, function (EntityProperty $el) use ($entityName) {
                return $el->getReferencedLink() === $entityName;
            });

            if (count($result) === 1) {
                return array_shift($result);
            }
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return EntityProperty|null
     */
    public function getPropertyByName(string $name): ?EntityProperty
    {
        $result = array_filter($this->getProperties(), function (EntityProperty $el) use ($name) {
            return $el->getName() === $name;
        });

        return count($result) === 1 ? array_shift($result): null;
    }

    /**
     * @param string $type
     *
     * @return EntityProperty[]
     */
    public function getPropertiesByType(string $type): array
    {
        $result = array_filter($this->getProperties(), function (EntityProperty $el) {
            return !is_null($el->getType());
        });

        return array_filter($result, function (EntityProperty $el) use ($type) {
            return $el->getType() === $type;
        });
    }

    /**
     * @param string $type
     * @param string $format
     *
     * @return EntityProperty[]
     */
    public function getPropertiesByTypeAndFormat(string $type, string $format): array
    {
        $result = array_filter($this->getPropertiesByType($type), function (EntityProperty $el) {
            return !is_null($el->getFormat());
        });

        return array_filter($result, function (EntityProperty $el) use ($format) {
            return $el->getFormat() === $format;
        });
    }

    /**
     * @return EntityProperty[]
     */
    public function getPropertiesEnum(): array
    {
        return array_filter($this->getProperties(), function (EntityProperty $el) {
            return !is_null($el->getEnum()) && is_array($el->getEnum());
        });
    }

    /**
     * @return EntityProperty[]
     */
    public function getOneToManyProperties(): array
    {
        return array_filter($this->getProperties(), function (EntityProperty $el) {
            return $el->isOneToMany();
        });
    }

    /**
     * @return array
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param array $traits
     *
     * @return Entity
     */
    public function setTraits(array $traits)
    {
        $this->traits = $traits;

        return $this;
    }

    /**
     * @return array
     */
    public function getRepositoryTraits(): array
    {
        return $this->repositoryTraits;
    }

    /**
     * @param array $repositoryTraits
     *
     * @return Entity
     */
    public function setRepositoryTraits(array $repositoryTraits)
    {
        $this->repositoryTraits = $repositoryTraits;

        return $this;
    }

    /**
     * @return array
     */
    public function getRepositoryInterfaces(): array
    {
        return $this->repositoryInterfaces;
    }

    /**
     * @param array $repositoryInterfaces
     *
     * @return Entity
     */
    public function setRepositoryInterfaces(array $repositoryInterfaces)
    {
        $this->repositoryInterfaces = $repositoryInterfaces;

        return $this;
    }

    /**
     * @return array
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }

    /**
     * @param array $annotations
     *
     * @return Entity
     */
    public function setAnnotations(array $annotations)
    {
        $this->annotations = $annotations;

        return $this;
    }

    /**
     * @return array
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @param array $interfaces
     *
     * @return Entity
     */
    public function setInterfaces(array $interfaces)
    {
        $this->interfaces = $interfaces;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGenerate(): bool
    {
        return $this->generate;
    }

    /**
     * @param bool $generate
     *
     * @return Entity
     */
    public function setGenerate(bool $generate)
    {
        $this->generate = $generate;

        return $this;
    }
}
