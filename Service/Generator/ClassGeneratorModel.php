<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorMethodModel;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorPropertyModel;

/**
 * Class ClassGeneratorModel
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
class ClassGeneratorModel implements ClassGeneratorModelInterface
{

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $nameSpace;

    /**
     * @var string
     */
    protected string $filePath;

    /**
     * @var array
     */
    protected array $useSection = [];

    /**
     * @var array
     */
    protected array $extentedClasses = [];

    /**
     * @var array
     */
    protected array $implementedInterfaces = [];

    /**
     * @var array
     */
    protected array $annotations = [];

    /**
     * @var array
     */
    protected array $traits = [];

    /**
     * @var array
     */
    protected array $constants = [];

    /**
     * @var array
     */
    protected array $properties = [];

    /**
     * @var array
     */
    protected array $methods = [];

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
     * @return ClassGeneratorModel
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
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
     * @return ClassGeneratorModel
     */
    public function setNameSpace(string $nameSpace)
    {
        $this->nameSpace = $nameSpace;

        return $this;
    }

    /**
     * @return array
     */
    public function getUseSection(): array
    {
        return $this->useSection;
    }

    /**
     * @param array $useSection
     *
     * @return ClassGeneratorModel
     */
    public function setUseSection(array $useSection)
    {
        $this->useSection = $useSection;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtentedClasses(): array
    {
        return $this->extentedClasses;
    }

    /**
     * @param array $extentedClasses
     *
     * @return ClassGeneratorModel
     */
    public function setExtentedClasses(array $extentedClasses)
    {
        $this->extentedClasses = $extentedClasses;

        return $this;
    }

    /**
     * @return array
     */
    public function getImplementedInterfaces(): array
    {
        return $this->implementedInterfaces;
    }

    /**
     * @param array $implementedInterfaces
     *
     * @return ClassGeneratorModel
     */
    public function setImplementedInterfaces(array $implementedInterfaces)
    {
        $this->implementedInterfaces = $implementedInterfaces;

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
     * @return ClassGeneratorModel
     */
    public function setAnnotations(array $annotations)
    {
        $this->annotations = $annotations;

        return $this;
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
     * @return ClassGeneratorModel
     */
    public function setTraits(array $traits)
    {
        $this->traits = $traits;

        return $this;
    }

    /**
     * @return array
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @param array $constants
     *
     * @return ClassGeneratorModel
     */
    public function setConstants(array $constants)
    {
        $this->constants = $constants;

        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     *
     * @return ClassGeneratorModel
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     *
     * @return ClassGeneratorModel
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     *
     * @return ClassGeneratorModel
     */
    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return GeneratorPropertyModel|null
     */
    public function getPropertyByName(string $name): ?GeneratorPropertyModel
    {
        $result = array_filter($this->getProperties(), function (GeneratorPropertyModel $el) use ($name) {
            return $el->getName() === $name;
        });

        return count($result) === 1 ? array_shift($result): null;
    }

    /**
     * @param string $name
     *
     * @return GeneratorMethodModel|null
     */
    public function getMethodByName(string $name): ?GeneratorMethodModel
    {
        $result = array_filter($this->getMethods(), function (GeneratorMethodModel $el) use ($name) {
            return $el->getName() === $name;
        });

        return count($result) === 1 ? array_shift($result): null;
    }
}
