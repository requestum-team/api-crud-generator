<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form;

/**
 * Class FormPropertyConstraintDto
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form
 */
class FormPropertyConstraintDto
{
    /** @var string[] */
    private array $uses = [];

    /** @var string[] */
    private array $contents = [];

    /**
     * @return string[]
     */
    public function getUses(): array
    {
        return $this->uses;
    }

    /**
     * @param string[] $uses
     *
     * @return $this
     */
    public function setUses(array $uses): self
    {
        $this->uses = $uses;

        return $this;
    }

    /**
     * @param string[] $uses
     *
     * @return $this
     */
    public function addUses(array $uses): self
    {
        $this->uses = array_merge($this->uses, $uses);

        return $this;
    }

    /**
     * @return string[]
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @param string[] $contents
     *
     * @return $this
     */
    public function setContents(array $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function addContent(string $content): self
    {
        $this->contents[] = $content;

        return $this;
    }
}
