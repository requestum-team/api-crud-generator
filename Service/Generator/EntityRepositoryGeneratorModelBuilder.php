<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

use Requestum\ApiGeneratorBundle\Model\Entity;

/**
 * Class EntityRepositoryGeneratorModelBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
class EntityRepositoryGeneratorModelBuilder
{
    /** @var string */
    const NAME_POSTFIX = 'Repository';

    /** @var string */
    protected string $bundleName;

    /** @var string */
    protected string $extendsClass;

    /** @var array */
    protected array $traits = [];

    /**
     * EntityRepositoryGeneratorModelBuilder constructor.
     *
     * @param string $bundleName
     */
    public function __construct(string $bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @param Entity $entity
     *
     * @return ClassGeneratorModelInterface
     */
    public function buildModel(Entity $entity): ClassGeneratorModelInterface
    {
        $nameSpace = implode('\\', [$this->bundleName, self::NAME_POSTFIX]);

        $this->baseTraits($entity->getRepositoryTraits());

        return (new ClassGeneratorModel())
            ->setName($entity->getName() . self::NAME_POSTFIX)
            ->setNameSpace($nameSpace)
            ->setFilePath($this->prepareFilePath($entity->getName()))
            ->setExtendsClass($this->extendsClass)
            ->setTraits($this->traits)
        ;
    }

    /**
     * @param string $extendsClass
     *s
     * @return $this
     */
    public function setExtendsClass(string $extendsClass): self
    {
        $this->extendsClass = $extendsClass;

        return $this;
    }

    /**
     * @param string $entityName
     *
     * @return string
     */
    private function prepareFilePath(string $entityName): string
    {
        return implode('.', [$entityName . self::NAME_POSTFIX, 'php']);
    }

    /**
     * @param array $traits
     *
     * @return $this
     */
    private function baseTraits(array $traits = []): self
    {
        array_push($this->traits, ...$traits);

        return $this;
    }
}
