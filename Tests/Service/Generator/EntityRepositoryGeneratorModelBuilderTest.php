<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
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

        $modelBuilder = (new EntityRepositoryGeneratorModelBuilder('AppBundle'))
            ->setExtendsClass('ApiRepository')
        ;

        $model = $modelBuilder->buildModel($structureTest);

        $phpGenerator = new PhpGenerator();
        $content =  $phpGenerator->generate($model);

        static::assertNotFalse(strpos($content, 'namespace AppBundle\Repository'));
        static::assertNotFalse(strpos($content, 'class ' . $elementName . 'Repository'));
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
}
