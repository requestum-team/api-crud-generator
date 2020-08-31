<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

/**
 * Class GeneratorModelBuilderAbstract
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
abstract class GeneratorModelBuilderAbstract
{
    /** @var string */
    protected string $bundleName;

    /** @var array */
    protected array $useSection = [];

    /** @var array */
    protected array $traits = [];

    /** @var array */
    protected array $constants = [];

    /** @var array */
    protected array $properties = [];

    /** @var array */
    protected array $methods = [];

    /**
     * GeneratorModelBuilderAbstract constructor.
     *
     * @param string $bundleName
     */
    public function __construct(string $bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @param object $subject
     *
     * @return ClassGeneratorModelInterface
     */
    abstract public function buildModel(object $subject): ClassGeneratorModelInterface;

    /**
     * @param array $traits
     *
     * @return $this
     */
    protected function addTraits(array $traits = []): self
    {
        foreach ($traits as $trait) {
            $explodeTrait = explode('\\', $trait);

            if (count($explodeTrait) > 1) {
                $this->useSection[] = $trait;
                $trait = array_pop($explodeTrait);
            }

            $this->traits[] = $trait;
        }

        return $this;
    }
}
