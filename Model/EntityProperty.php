<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class EntityProperty
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class EntityProperty
{
    const GENERATION_STRATEGY_AUTO = 'auto';
    const GENERATION_STRATEGY_SEQUENCE = 'sequence';
    const GENERATION_STRATEGY_IDENTITY = 'identity';
    const GENERATION_STRATEGY_UUID = 'uuid';
    const GENERATION_STRATEGY_TABLE = 'table';
    const GENERATION_STRATEGY_NONE = 'none';
    const GENERATION_STRATEGY_CUSTOM = 'custom';

    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';

    /**
     * @var Entity
     */
    private Entity $entity;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $databasePropertyName;

    /**
     * @var string
     */
    private ?string $description = null;

    /**
     * @var string
     * @example string, number, integer, boolean, array, object
     */
    private ?string $type = null;

    /**
     * @var string
     * @example float, double, int32, int64, date, date-time,
     * password, byte, binary, email, uuid, uri, hostname, ipv4, ipv6
     */
    private ?string $format = null;

    /**
     * @var string
     * @example string, number, integer, boolean, array, object
     */
    private ?string $itemsType = null;

    /**
     * @var string[]
     */
    private array $enum = [];

    /**
     * applies only for string type
     *
     * @var integer
     */
    private ?int $minLength = null;

    /**
     * applies only for string type
     *
     * @var integer
     */
    private ?int $maxLength = null;

    /**
     * applies only for string type
     *
     * @var string
     */
    private ?string $pattern = null;

    /**
     * applies only for integer or number types
     *
     * @var integer
     */
    private ?int $minimum = null;

    /**
     * applies only for integer or number types
     *
     * @var integer
     */
    private ?int $maximum = null;

    /**
     * applies only for array type
     *
     * @var integer
     */
    private ?int $minItems = null;

    /**
     * applies only for array type
     *
     * @var integer
     */
    private ?int $maxItems = null;

    /**
     * @var bool
     */
    private bool $primary = false;

    /**
     * @var bool
     */
    private bool $foreignKey = false;

    /**
     * @var bool
     */
    private bool $backRefColumn = false;

    /**
     * @var bool | null
     */
    private ?bool $useList = null;

    /**
     * @var bool
     */
    private bool $required = false;

    /**
     * @var bool
     */
    private bool $nullable = false;

    /**
     * @var bool
     */
    private bool $readOnly = false;

    /**
     * @var string|null
     */
    private ?string $generationStrategy = null;

    /**
     * @var mixed
     */
    private $defaultValue = null;

    /**
     * @var bool
     */
    private bool $unsigned = true;

    /**
     * @var bool
     */
    private bool $isCreateDate = false;

    /**
     * @var bool
     */
    private bool $isUpdateDate = false;

    /**
     * @var bool
     */
    private bool $oneToMany = false;

    /**
     * @var bool
     */
    private bool $manyToOne = false;

    /**
     * @var bool
     */
    private bool $oneToOne = false;

    /**
     * @var bool
     */
    private bool $manyToMany = false;

    /**
     * @var string|null
     */
    private ?string $referencedLink = null;

    /**
     * @var string|null
     */
    private ?string $backRef = null;

    /**
     * If this column is foreign key then it references some other column,
     * and this property will contain reference to this column.
     *
     * @var EntityProperty|null
     */
    private ?EntityProperty $referencedColumn = null;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return EntityProperty
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return EntityProperty
     */
    public function setType(?string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $type
     * @example string, number, integer, boolean, array, object
     *
     * @return bool
     */
    public function checkType(string $type): bool
    {
        if (is_null($this->getType())) {
            return false;
        }

        return $this->type === $type;
    }

    /**
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @param string|null $format
     *
     * @return EntityProperty
     */
    public function setFormat(?string $format)
    {
        $this->format = $format;

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
     * @return string[]
     */
    public function getEnum(): array
    {
        return $this->enum;
    }

    /**
     * @param string[] $enum
     *
     * @return EntityProperty
     */
    public function setEnum(array $enum)
    {
        $this->enum = $enum;

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
        if (!$this->checkType(EntityProperty::TYPE_STRING)) {
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
        if (!$this->checkType(EntityProperty::TYPE_STRING)) {
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
        if (!$this->checkType(EntityProperty::TYPE_STRING)) {
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
        if (!$this->checkType(EntityProperty::TYPE_INTEGER) && !$this->checkType(EntityProperty::TYPE_NUMBER)) {
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
        if (!$this->checkType(EntityProperty::TYPE_INTEGER) && !$this->checkType(EntityProperty::TYPE_NUMBER)) {
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
        if (!$this->checkType(EntityProperty::TYPE_ARRAY)) {
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
        if (!$this->checkType(EntityProperty::TYPE_ARRAY)) {
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
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return EntityProperty
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;

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
     * @return string|null
     */
    public function getReferencedLink(): ?string
    {
        return $this->referencedLink;
    }

    /**
     * @param string|null $referencedLink
     *
     * @return EntityProperty
     */
    public function setReferencedLink(?string $referencedLink)
    {
        $this->referencedLink = $referencedLink;

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
