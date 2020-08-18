<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Generator\AccessLevelEnum;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorMethodModel;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorParameterModel;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorPropertyModel;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorStrategy;

/**
 * Class EntityGeneratorModelBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
class EntityGeneratorModelBuilder
{
    /**
     * @var string
     */
    protected string $bundleName;

    /**
     * @var array
     */
    protected array $useSection = [];

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
     * @var AnnotationGeneratorStrategy
     */
    protected AnnotationGeneratorStrategy $annotationGeneratorStrategy;

    /**
     * EntityGeneratorModelBuilder constructor.
     *
     * @param string $bundleName
     */
    public function __construct(string $bundleName)
    {
        $this->bundleName = $bundleName;
        $this->annotationGeneratorStrategy = new AnnotationGeneratorStrategy();
    }

    /**
     * @param Entity $entity
     *
     * @return ClassGeneratorModelInterface
     */
    public function buildModel(Entity $entity): ClassGeneratorModelInterface
    {

        $this->baseUseSection($entity->getName());
        $this->baseAnnotations($entity->getName(), $entity->getTableName());
        $this->detectConstructor($entity);
        $this->prepareConstants($entity);
        $this->prepareProperties($entity->getProperties());
        $this->prepareMethods($entity->getProperties());

        $nameSpace = implode('\\', [$this->bundleName, 'Entity']);
        $model = new ClassGeneratorModel();

        $model
            ->setName($entity->getName())
            ->setNameSpace($nameSpace)
            ->setFilePath(
                $this->prepareFilePath($entity->getName())
            )
            ->setAnnotations($this->annotations)
            ->setUseSection($this->useSection)
            ->setConstants($this->constants)
            ->setProperties($this->properties)
            ->setMethods($this->methods)
        ;

        return $model;
    }

    /**
     * @param string $entityName
     */
    private function baseUseSection(string $entityName)
    {
        $this->useSection[] = 'Doctrine\ORM\Mapping as ORM';
        $this->useSection[] = 'Symfony\Component\Serializer\Annotation\Groups';
    }

    /**
     * @param string $entityName
     * @param string $tableName
     */
    private function baseAnnotations(string $entityName, string $tableName)
    {
        $this->annotations[] = sprintf('@ORM\Table(name="%s")', $tableName);

        // AppBundle\Repository\SomeRepository
        $repositoryClass = implode('\\', [$this->bundleName, 'Repository', implode('', [$entityName, 'Repository'])]);
        $this->annotations[] = sprintf('@ORM\Entity(repositoryClass="%s")', $repositoryClass);
    }

    /**
     * @param Entity $entity
     */
    private function prepareConstants(Entity $entity)
    {
        $properties = $entity->getPropertiesEnum();
        if (is_array($properties) && !empty($properties)) {
            /** @var  $property */
            foreach ($properties as $property) {
                if (!is_array($property->getEnum()) || empty($property->getEnum())) {
                    continue;
                }

                foreach ($property->getEnum() as $enum) {
                    $constantName = implode('_', [strtoupper($property->getDatabasePropertyName()), strtoupper($enum)]);
                    $this->constants[] = [
                        'name' => $constantName,
                        'value' => strtolower($enum)
                    ];
                }
            }
        }
    }

    /**
     * @param Entity $entity
     */
    private function detectConstructor(Entity $entity)
    {
        $properties = $entity->getOneToManyProperties();
        if (is_array($properties) && count($properties) > 0) {
            $body = [];
            /** @var EntityProperty $property */
            foreach ($properties as $property) {
                $body[] = sprintf('$this->%s = new ArrayCollection();' . PHP_EOL , $property->getName());
            }
            $construct = new GeneratorMethodModel();
            $construct
                ->setName('__construct')
                ->setAccessLevel(AccessLevelEnum::ACCESS_LELEV_PUBLIC)
                ->setBody(implode('', $body))
            ;

            $this->methods[] = $construct;
            $this->useSection[] = 'Doctrine\Common\Collections\ArrayCollection';
        }
    }

    /**
     * @param string $entityName
     *
     * @return string
     */
    private function prepareFilePath(string $entityName)
    {
        return implode('.', [$entityName, 'php']);
    }

    /**
     * @param array $properties
     */
    private function prepareProperties(array $properties)
    {
        /** @var EntityProperty $entityProperty */
        foreach ($properties as $entityProperty) {
            $property = new GeneratorPropertyModel();
            $property
                ->setName($entityProperty->getName())
                ->setAccessLevel(AccessLevelEnum::ACCESS_LELEV_PROTECTED)
                ->setAttributs(
                    $this->getPropertyAttributs($entityProperty)
                )
            ;

            $this->properties[] = $property;
        }
    }

    /**
     * @param EntityProperty $entityProperty
     *
     * @return array
     */
    private function getPropertyAttributs(EntityProperty $entityProperty): array
    {
        $result[] = [
            'name' => 'var',
            'description' => sprintf('%s $%s', $entityProperty->getType(), $entityProperty->getName())
        ];

        $generator = $this->annotationGeneratorStrategy->getAnnotationGenerator(
            $this->detectPropertyType($entityProperty)
        );

        foreach ($generator->generate($entityProperty) as $attribut) {
            $result[] = [
                'name' => $attribut,
            ];
        }

        return $result;
    }

    /**
     * @param array $properties
     */
    private function prepareMethods(array $properties)
    {
        /** @var EntityProperty $entityProperty */
        foreach ($properties as $entityProperty) {
            $setter = new GeneratorMethodModel();
            $getter = new GeneratorMethodModel();

            $parameterType = $this->prepareParameterType($entityProperty);

            $setterInputParameter = new GeneratorParameterModel();
            $setterInputParameter
                ->setName($entityProperty->getName())
                ->setType($parameterType)
            ;

            $setterReturnParameter = new GeneratorParameterModel();
            $setterReturnParameter
                ->setType($entityProperty->getEntity()->getName())
                ->setIsReturn(true)
            ;

            $setter
                ->setName(StringHelper::makeSetterName($entityProperty->getName()))
                ->setAccessLevel(AccessLevelEnum::ACCESS_LELEV_PUBLIC)
                ->setBody(sprintf('$this->%s = $%s;' . PHP_EOL . PHP_EOL . 'return $this;', $entityProperty->getName(), $entityProperty->getName()))
                ->addParameters($setterInputParameter)
                ->addParameters($setterReturnParameter)
            ;

            $getterReturnParameter = new GeneratorParameterModel();
            $getterReturnParameter
                ->setType($parameterType)
                ->setIsReturn(true)
            ;

            $getter
                ->setName(StringHelper::makeGetterName($entityProperty->getName()))
                ->setAccessLevel(AccessLevelEnum::ACCESS_LELEV_PUBLIC)
                ->setBody(sprintf('return $this->%s;', $entityProperty->getName()))
                ->addParameters($getterReturnParameter)
            ;

            $this->methods[] = $setter;
            $this->methods[] = $getter;
        }
    }

    /**
     * todo
     * @param EntityProperty $entityProperty
     *
     * @return string
     */
    private function prepareParameterType(EntityProperty $entityProperty): string
    {
        $type = '';
        if (!is_null($entityProperty->getType())) {
            $type = $entityProperty->getType();

            switch ($entityProperty->getType()) {
                case PropertyTypeEnum::TYPE_INTEGER:
                    $type = 'int';
                    break;

                case PropertyTypeEnum::TYPE_BOOLEAN:
                    $type = 'bool';
                    break;

                case PropertyTypeEnum::TYPE_NUMBER:
                    $type = 'float';
                    break;
            }

        }

        if ($entityProperty->isForeignKey()) {
            $type = 'int';
        }

        if ($entityProperty->isOneToMany()) {
            $type = 'Doctrine\Common\Collections\ArrayCollection';
        }

        return $type;
    }

    /**
     * @param EntityProperty $entityProperty
     *
     * @return string
     */
    private function detectPropertyType(EntityProperty $entityProperty): string
    {
        if ($entityProperty->isPrimary()) {
            return PropertyTypeEnum::TYPE_PRIMARY_AUTO;
        }

        if ($entityProperty->getType() === PropertyTypeEnum::TYPE_INTEGER) {
            return PropertyTypeEnum::TYPE_INTEGER;
        }

        if ($entityProperty->getType() === PropertyTypeEnum::TYPE_NUMBER) {
            if (!is_null($entityProperty->getFormat()) && $entityProperty->getFormat() === PropertyTypeEnum::TYPE_DOUBLE) {
                return PropertyTypeEnum::TYPE_DECIMAL;
            }

            return PropertyTypeEnum::TYPE_FLOAT;
        }

        return PropertyTypeEnum::TYPE_STRING;
    }
}
