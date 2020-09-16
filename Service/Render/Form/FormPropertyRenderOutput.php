<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form;

/**
 * Class FormPropertyRenderOutput
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form
 */
class FormPropertyRenderOutput
{
    /** @var array */
    protected array $useSections = [];

    /** @var string */
    protected string $content;

    /**
     * @return array
     */
    public function getUseSections(): array
    {
        return $this->useSections;
    }

    /**
     * @param array $useSections
     *
     * @return $this
     */
    public function setUseSections(array $useSections): self
    {
        $this->useSections = $useSections;

        return $this;
    }

    /**
     * @param array $useSections
     *
     * @return $this
     */
    public function addUseSections(array $useSections): self
    {
        $this->useSections = array_merge($this->useSections, $useSections);

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
