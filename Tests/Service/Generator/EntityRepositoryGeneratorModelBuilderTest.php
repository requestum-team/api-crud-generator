<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\AccessLevelException;
use Requestum\ApiGeneratorBundle\Exception\SubjectTypeException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\EntityGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\EntityRepositoryGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\PhpGenerator;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class EntityRepositoryGeneratorModelBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Generator
 */
class EntityRepositoryGeneratorModelBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @dataProvider structureProvider
     *
     * @param string $filename
     * @param string $elementName
     *
     * @throws \Exception
     */
    public function testStructure(string $filename, string $elementName)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getSchemasAndRequestBodiesCollection($filePath)
        );

        /** @var Entity $structureTest */
        $structureTest = $collection->findElement($elementName);
        $modelBuilder = (new EntityRepositoryGeneratorModelBuilder('AppBundle'));
        $model = $modelBuilder->buildModel($structureTest);
        $phpGenerator = new PhpGenerator();
        $content =  $phpGenerator->generate($model);

        static::assertNotFalse(strpos($content, 'namespace AppBundle\Repository'));
        static::assertNotFalse(
            strpos($content, 'class ' . $elementName . EntityRepositoryGeneratorModelBuilder::NAME_POSTFIX)
        );
        static::assertNotFalse(strpos($content, 'extends ApiRepository'));
        static::assertNotFalse(strpos($content, 'AppBundle\AbsRepositoryTrait'));
        static::assertNotFalse(strpos($content, 'AppBundle\QweRepositoryTrait'));
    }

    /**
     * @return string[][]
     */
    public function structureProvider()
    {
        return [
            [
                'entity-generator-model-structure.yaml',
                'StructureTest',
            ],
        ];
    }

    /**
     * @param object $subject
     * @param string $exception
     * @param string $message
     *
     * @throws AccessLevelException
     * @dataProvider modelTypeBuilderExceptionProvider
     */
    public function testModelBuilderTypeException(object $subject, string $exception, string $message)
    {
        static::expectException($exception);
        static::expectExceptionMessage($message);

        $modelBuilder = new EntityGeneratorModelBuilder('AppBundle');
        $modelBuilder->buildModel($subject);
    }

    /**
     * @return string[][]
     */
    public function modelTypeBuilderExceptionProvider()
    {
        return [
            [
                new Form(),
                SubjectTypeException::class,
                sprintf(
                    'Wrong subject type: %s. Expected class type: %s.',
                    Form::class,
                    Entity::class
                )
            ],
        ];
    }
}
