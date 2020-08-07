<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class BaseAbstractProperty
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
abstract class BaseAbstractProperty
{
    /**
     * @var string
     */
    protected ?string $description = null;

    /**
     * @var string
     * @example string, number, integer, boolean, array, object
     */
    protected ?string $type = null;

    /**
     * @var string
     * @example float, double, int32, int64, date, date-time,
     * password, byte, binary, email, uuid, uri, hostname, ipv4, ipv6
     */
    protected ?string $format = null;

    /**
     * @var string[]
     */
    protected array $enum = [];

    /**
     * @var bool
     */
    protected bool $required = false;

    /**
     * @var string|null
     */
    protected ?string $referencedLink = null;

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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setFormat(?string $format)
    {
        $this->format = $format;

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
     * @return $this
     */
    public function setEnum(array $enum)
    {
        $this->enum = $enum;

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
     * @return $this
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;

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
     * @return $this
     */
    public function setReferencedLink(?string $referencedLink)
    {
        $this->referencedLink = $referencedLink;

        return $this;
    }
}
