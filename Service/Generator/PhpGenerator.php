<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\DocBlock\Tag;

use Requestum\ApiGeneratorBundle\Model\Generator\AccessLevelEnum;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorMethodModel;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorParameterModel;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorPropertyModel;

/**
 * Class PhpGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
class PhpGenerator
{
    /**
     * @param ClassGeneratorModelInterface $model
     *
     * @return string
     */
    public function generate(ClassGeneratorModelInterface $model): string
    {
        $file = new FileGenerator([
            'namespace' => $model->getNameSpace(),
            'classes' => [
                $this->makeClass($model)
            ],
            'uses' => $model->getUseSection(),
        ]);

        return $file->generate();
    }

    /**
     * @param ClassGeneratorModelInterface $model
     *
     * @return ClassGenerator
     */
    private function makeClass(ClassGeneratorModelInterface $model): ClassGenerator
    {
        $class = new ClassGenerator();
        $tags = array_map(function ($el) {
            return ['name' => $el];
        }, $model->getAnnotations());

        $docblock = DocBlockGenerator::fromArray([
            'shortDescription' => sprintf('Class %s', $model->getName()),
            'tags' => $tags,
        ]);

        $class
            ->setName($model->getName())
            ->setExtendedClass($model->getExtendsClass())
            ->setImplementedInterfaces($model->getImplementedInterfaces())
            ->setDocblock($docblock)
            ->addConstants($model->getConstants())
            ->addProperties(
                $this->prepareProperties($model->getProperties())
            )
            ->addMethods(
                $this->prepareMethods($model->getMethods())
            )
            ->addTraits($model->getTraits())
        ;

        return $class;
    }

    /**
     * @param array $properties
     *
     * @return array
     */
    private function prepareProperties(array $properties): array
    {
        $result = [];
        /** @var GeneratorPropertyModel $property */
        foreach ($properties as $property) {
            $flag = PropertyGenerator::FLAG_PUBLIC;
            switch ($property->getAccessLevel()) {
                case AccessLevelEnum::ACCESS_LEVEL_PROTECTED:
                    $flag = PropertyGenerator::FLAG_PROTECTED;
                    break;

                case AccessLevelEnum::ACCESS_LEVEL_PRIVATE:
                    $flag = PropertyGenerator::FLAG_PRIVATE;
                    break;
            }
            $propertyGenerator = new PropertyGenerator($property->getName(), $property->getDefaultValue(), $flag);
            if (is_null($property->getDefaultValue())) {
                $propertyGenerator->omitDefaultValue(true);
            }

            if (!empty($property->getAttributes()) && is_array($property->getAttributes())) {
                $docblock = DocBlockGenerator::fromArray([
                    'tags' => $property->getAttributes(),
                ]);
                $propertyGenerator->setDocBlock($docblock);
            }


            $result[] = $propertyGenerator;
        }

        return $result;
    }

    /**
     * @param array $methods
     *
     * @return array
     */
    private function prepareMethods(array $methods): array
    {
        $result = [];

        /** @var GeneratorMethodModel $method */
        foreach ($methods as $method) {
            $flag = PropertyGenerator::FLAG_PUBLIC;
            switch ($method->getAccessLevel()) {
                case AccessLevelEnum::ACCESS_LEVEL_PROTECTED:
                    $flag = PropertyGenerator::FLAG_PROTECTED;
                    break;

                case AccessLevelEnum::ACCESS_LEVEL_PRIVATE:
                    $flag = PropertyGenerator::FLAG_PRIVATE;
                    break;
            }

            $result[] = new MethodGenerator(
                $method->getName(),
                $this->prepareParams(
                    $method->getInputParameters()
                ),
                $flag,
                $method->getBody(),
                DocBlockGenerator::fromArray([
                    'tags' => $this->prepareDocBlockParams(
                        $method->getParameters()
                    ),
                ])
            );
        }

        return $result;
    }

    /**
     * @param GeneratorParameterModel[] $parameters
     *
     * @return array
     */
    private function prepareParams(array $parameters): array
    {
        $result = [];
        /** @var GeneratorParameterModel $parameter */
        foreach ($parameters as $parameter) {
            $param = new ParameterGenerator(
                $parameter->getName(),
                $parameter->getType(),
                $parameter->getDefaultValue(),
                $parameter->getPosition(),
            );

            $result[] = $param;
        }

        return $result;
    }

    /**
     * @param GeneratorParameterModel[] $parameters
     *
     * @return array
     */
    private function prepareDocBlockParams(array $parameters): array
    {
        $result = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (preg_match("/^\?/", $type)) {
                $type = preg_replace("/^\?/", '', $type) . '|null';
            }

            if ($parameter->isReturn()) {
                $tag = new Tag\ReturnTag($type);
            } else {
                $tag = new Tag\ParamTag(
                    $parameter->getName(),
                    $type
                );
            }

            $result[] = $tag;
        }

        return $result;
    }
}
