<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\EntityGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\PhpGenerator;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class EntityGeneratorModelBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Generator
 */
class EntityGeneratorModelBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @dataProvider structureProvider
     */
    public function testStructure(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $structureTest */
        $structureTest = $collection->findElement('StructureTest');

        $modelBuilder = new EntityGeneratorModelBuilder('AppBundle');
        $model = $modelBuilder->buildModel($structureTest);

        static::assertEquals('StructureTest', $model->getName());
        static::assertEquals('AppBundle\Entity', $model->getNameSpace());
        static::assertEquals('StructureTest.php', $model->getFilePath());
        static::assertContains('Doctrine\ORM\Mapping as ORM;', $model->getUseSection());
        static::assertContains('@ORM\Table(name="structure_test")', $model->getAnnotations());

        $phpGenerator = new PhpGenerator();
        file_put_contents('Test.php', $phpGenerator->generate($model));

//        var_dump($model);
    }

    public function structureProvider()
    {
        return [
            [
                'entity-generator-model-structure.yaml'
            ],
        ];
    }
}
