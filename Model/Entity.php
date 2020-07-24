<?php

namespace Requestum\ApiGeneratorBundle\Model;

class Entity
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $tableName;

    /**
     * @var EntityProperty[]
     */
    private array $foreignKeys = [];

    /**
     * @var EntityProperty[]
     */
    private array $primaryColumns = [];

    /**
     * @var EntityProperty[]
     */
    private array $uniques = [];

    /**
     * @var EntityProperty[]
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
        $this->foreignKeys[] = $foreignKey;

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
        $this->primaryColumns[] = $primaryColumn;

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
        $this->uniques[] = $unique;

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
}
