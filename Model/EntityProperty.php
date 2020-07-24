<?php

namespace Requestum\ApiGeneratorBundle\Model;

class EntityProperty
{
    const GENERATION_STRATEGY_AUTO = 'auto';
    const GENERATION_STRATEGY_SEQUENCE = 'sequence';
    const GENERATION_STRATEGY_IDENTITY = 'identity';
    const GENERATION_STRATEGY_UUID = 'uuid';
    const GENERATION_STRATEGY_TABLE = 'table';
    const GENERATION_STRATEGY_NONE = 'none';
    const GENERATION_STRATEGY_CUSTOM = 'custom';

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private ?string $format = null;

    /**
     * @var integer
     */
    private ?int $length = null;

    /**
     * @var bool
     */
    private bool $isPrimary = false;

    /**
     * @var bool
     */
    private bool $isNullable = false;

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
     * If this column is foreign key then it references some other column,
     * and this property will contain reference to this column.
     *
     * @var EntityProperty|null
     */
    private ?EntityProperty $referencedColumn = null;

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return EntityProperty
     */
    public function setType(string $type)
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
     * @return EntityProperty
     */
    public function setFormat(?string $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param ?int $length
     *
     * @return EntityProperty
     */
    public function setLength(?int $length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    /**
     * @param bool $isPrimary
     *
     * @return EntityProperty
     */
    public function setIsPrimary(bool $isPrimary)
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /**
     * @param bool $isNullable
     *
     * @return EntityProperty
     */
    public function setIsNullable(bool $isNullable)
    {
        $this->isNullable = $isNullable;

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
}
