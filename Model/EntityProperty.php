<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class EntityProperty
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class EntityProperty extends BaseAbstractProperty
{
    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $databasePropertyName;

    /**
     * @var string
     * @example string, number, integer, boolean, array, object
     */
    protected ?string $itemsType = null;

    /**
     * applies only for string type
     *
     * @var integer
     */
    protected ?int $minLength = null;

    /**
     * applies only for string type
     *
     * @var integer
     */
    protected ?int $maxLength = null;

    /**
     * applies only for string type
     *
     * @var string
     */
    protected ?string $pattern = null;

    /**
     * applies only for integer or number types
     *
     * @var integer
     */
    protected ?int $minimum = null;

    /**
     * applies only for integer or number types
     *
     * @var integer
     */
    protected ?int $maximum = null;

    /**
     * applies only for array type
     *
     * @var integer
     */
    protected ?int $minItems = null;

    /**
     * applies only for array type
     *
     * @var integer
     */
    protected ?int $maxItems = null;

    /**
     * @var bool
     */
    protected bool $primary = false;

    /**
     * @var bool
     */
    protected bool $foreignKey = false;

    /**
     * @var bool
     */
    protected bool $backRefColumn = false;

    /**
     * @var bool | null
     */
    protected ?bool $useList = null;

    /**
     * @var bool
     */
    protected bool $nullable = false;

    /**
     * @var bool
     */
    protected bool $readOnly = false;

    /**
     * @var string|null
     */
    protected ?string $generationStrategy = null;

    /**
     * @var mixed
     */
    protected $defaultValue = null;

    /**
     * @var bool
     */
    protected bool $unsigned = true;

    /**
     * @var bool
     */
    protected bool $isCreateDate = false;

    /**
     * @var bool
     */
    protected bool $isUpdateDate = false;

    /**
     * @var bool
     */
    protected bool $oneToMany = false;

    /**
     * @var bool
     */
    protected bool $manyToOne = false;

    /**
     * @var bool
     */
    protected bool $oneToOne = false;

    /**
     * @var bool
     */
    protected bool $manyToMany = false;

    /**
     * @var string|null
     */
    protected ?string $backRef = null;

    /**
     * If this column is foreign key then it references some other column,
     * and this property will contain reference to this column.
     *
     * @var EntityProperty|null
     */
    protected ?EntityProperty $referencedColumn = null;

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     *
     * @return EntityProperty
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;

        return $this;
    }

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
     * @return EntityProperty
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDatabasePropertyName(): string
    {
        return $this->databasePropertyName;
    }

    /**
     * @param string $databasePropertyName
     *
     * @return EntityProperty
     */
    public function setDatabasePropertyName(string $databasePropertyName)
    {
        $this->databasePropertyName = $databasePropertyName;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getItemsType(): ?string
    {
        return $this->itemsType;
    }

    /**
     * @param string|null $itemsType
     *
     * @return EntityProperty
     */
    public function setItemsType(?string $itemsType)
    {
        $this->itemsType = $itemsType;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * @param int $minLength
     *
     * @return EntityProperty
     */
    public function setMinLength(?int $minLength)
    {
        if (!$this->checkType(PropertyTypeEnum::TYPE_STRING)) {
            throw new \Exception(
                'Min length applies only for type string. Use minimum for types integer and number or minItems for type array'
            );
        }

        $this->minLength = $minLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     *
     * @return EntityProperty
     */
    public function setMaxLength(?int $maxLength)
    {
        if (!$this->checkType(PropertyTypeEnum::TYPE_STRING)) {
            throw new \Exception(
                'Max length applies only for type string. Use maximum for types integer and number or maxItems for type array'
            );
        }

        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     *
     * @return EntityProperty
     */
    public function setPattern(?string $pattern)
    {
        if (!$this->checkType(PropertyTypeEnum::TYPE_STRING)) {
            throw new \Exception(
                'Pattern applies only for type string.'
            );
        }

        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    /**
     * @param int $minimum
     *
     * @return EntityProperty
     */
    public function setMinimum(?int $minimum)
    {
        if (!$this->checkType(PropertyTypeEnum::TYPE_INTEGER) && !$this->checkType(PropertyTypeEnum::TYPE_NUMBER)) {
            throw new \Exception(
                'Minimum applies only for integer or number types. Use minLength for type string or mimItems type array'
            );
        }

        $this->minimum = $minimum;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    /**
     * @param int|null $maximum
     *
     * @return EntityProperty
     */
    public function setMaximum(?int $maximum)
    {
        if (!$this->checkType(PropertyTypeEnum::TYPE_INTEGER) && !$this->checkType(PropertyTypeEnum::TYPE_NUMBER)) {
            throw new \Exception(
                'Maximum applies only for integer or number types. Use maxLength for type string or maxItems for type array'
            );
        }

        $this->maximum = $maximum;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinItems(): ?int
    {
        return $this->minItems;
    }

    /**
     * @param int|null $minItems
     *
     * @return EntityProperty
     */
    public function setMinItems(?int $minItems)
    {
        if (!$this->checkType(PropertyTypeEnum::TYPE_ARRAY)) {
            throw new \Exception(
                'Min items applies only for type array. Use minLength for type string or minimum for types integer and number'
            );
        }

        $this->minItems = $minItems;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    /**
     * @param int|null $maxItems
     *
     * @return EntityProperty
     */
    public function setMaxItems(?int $maxItems)
    {
        if (!$this->checkType(PropertyTypeEnum::TYPE_ARRAY)) {
            throw new \Exception(
                'Max items applies only for type array. Use maxLength for type string or maximum for types integer and number'
            );
        }

        $this->maxItems = $maxItems;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->primary;
    }

    /**
     * @param bool $primary
     *
     * @return EntityProperty
     */
    public function setPrimary(bool $primary)
    {
        $this->primary = $primary;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     *
     * @return EntityProperty
     */
    public function setNullable(bool $nullable)
    {
        if ($nullable) {
            $this->setRequired(false);
        }

        $this->nullable = $nullable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     *
     * @return EntityProperty
     */
    public function setReadOnly(bool $readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGenerationStrategy(): ?string
    {
        return $this->generationStrategy;
    }

    /**
     * @param string|null $generationStrategy
     *
     * @return EntityProperty
     */
    public function setGenerationStrategy(?string $generationStrategy)
    {
        $this->generationStrategy = $generationStrategy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     *
     * @return EntityProperty
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    /**
     * @param bool $unsigned
     *
     * @return EntityProperty
     */
    public function setUnsigned(bool $unsigned)
    {
        $this->unsigned = $unsigned;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateDate(): bool
    {
        return $this->isCreateDate;
    }

    /**
     * @param bool $isCreateDate
     *
     * @return EntityProperty
     */
    public function setIsCreateDate(bool $isCreateDate)
    {
        $this->isCreateDate = $isCreateDate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUpdateDate(): bool
    {
        return $this->isUpdateDate;
    }

    /**
     * @param bool $isUpdateDate
     *
     * @return EntityProperty
     */
    public function setIsUpdateDate(bool $isUpdateDate)
    {
        $this->isUpdateDate = $isUpdateDate;

        return $this;
    }

    /**
     * @return EntityProperty|null
     */
    public function getReferencedColumn(): ?EntityProperty
    {
        return $this->referencedColumn;
    }

    /**
     * @param EntityProperty|null $referencedColumn
     *
     * @return EntityProperty
     */
    public function setReferencedColumn(?EntityProperty $referencedColumn)
    {
        $this->referencedColumn = $referencedColumn;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOneToMany(): bool
    {
        return $this->oneToMany;
    }

    /**
     * @param bool $oneToMany
     *
     * @return EntityProperty
     */
    public function setOneToMany(bool $oneToMany)
    {
        $this->oneToMany = $oneToMany;

        return $this;
    }

    /**
     * @return bool
     */
    public function isManyToOne(): bool
    {
        return $this->manyToOne;
    }

    /**
     * @param bool $manyToOne
     *
     * @return EntityProperty
     */
    public function setManyToOne(bool $manyToOne)
    {
        $this->manyToOne = $manyToOne;

        $this->getEntity()->addForeignKey($this);

        return $this;
    }

    /**
     * @return bool
     */
    public function isOneToOne(): bool
    {
        return $this->oneToOne;
    }

    /**
     * @param bool $oneToOne
     *
     * @return EntityProperty
     */
    public function setOneToOne(bool $oneToOne)
    {
        $this->oneToOne = $oneToOne;

        return $this;
    }

    /**
     * @return bool
     */
    public function isManyToMany(): bool
    {
        return $this->manyToMany;
    }

    /**
     * @param bool $manyToMany
     *
     * @return EntityProperty
     */
    public function setManyToMany(bool $manyToMany)
    {
        $this->manyToMany = $manyToMany;

        return $this;
    }

    /**
     * @return bool
     */
    public function isForeignKey(): bool
    {
        return $this->foreignKey;
    }

    /**
     * @param bool $foreignKey
     *
     * @return EntityProperty
     */
    public function setForeignKey(bool $foreignKey)
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBackRefColumn(): bool
    {
        return $this->backRefColumn;
    }

    /**
     * @param bool $backRefColumn
     *
     * @return EntityProperty
     */
    public function setBackRefColumn(bool $backRefColumn)
    {
        $this->backRefColumn = $backRefColumn;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isUseList(): ?bool
    {
        return $this->useList;
    }

    /**
     * @param bool|null $useList
     *
     * @return EntityProperty
     */
    public function setUseList(?bool $useList)
    {
        $this->useList = $useList;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBackRef(): ?string
    {
        return $this->backRef;
    }

    /**
     * @param string|null $backRef
     *
     * @return EntityProperty
     */
    public function setBackRef(?string $backRef)
    {
        $this->backRef = $backRef;

        return $this;
    }
}
