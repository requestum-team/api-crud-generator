<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

use Requestum\ApiGeneratorBundle\Exception\SubjectTypeException;
use Requestum\ApiGeneratorBundle\Model\Entity;

/**
 * Class EntityRepositoryGeneratorModelBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
class EntityRepositoryGeneratorModelBuilder extends GeneratorModelBuilderAbstract
{
    /** @var string */
    const NAME_POSTFIX = 'Repository';

    /**
     * @param Entity|object $entity
     *
     * @return ClassGeneratorModelInterface
     */
    public function buildModel(object $entity): ClassGeneratorModelInterface
    {
        if (!$entity instanceof Entity) {
            throw new SubjectTypeException($entity, Entity::class);
        }

        $nameSpace = implode('\\', [$this->bundleName, self::NAME_POSTFIX]);

        $this->baseUseSection();
        $this->baseTraits();
        $this->addTraits($entity->getRepositoryTraits());

        return (new ClassGeneratorModel())
            ->setName($entity->getName() . self::NAME_POSTFIX)
            ->setNameSpace($nameSpace)
            ->setFilePath($this->prepareFilePath($entity->getName()))
            ->setExtendsClass('EntityRepository')
            ->setImplementedInterfaces(['FilterableRepositoryInterface',])
            ->setUseSection($this->useSection)
            ->setTraits($this->traits)
        ;
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

    private function baseUseSection()
    {
        $this->addUseSections([
            'Requestum\ApiBundle\Repository\ApiRepositoryTrait',
            'Requestum\ApiBundle\Repository\FilterableRepositoryInterface',
            'Doctrine\ORM\EntityRepository',
        ]);
    }

    private function baseTraits()
    {
        $this->addTraits([
            'ApiRepositoryTrait',
        ]);
    }
}
