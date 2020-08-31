<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Exception\SubjectTypeException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\FormGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\PhpGenerator;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class FormGeneratorModelBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Generator
 */
class FormGeneratorModelBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @dataProvider structureProvider
     *
     * @param string $filename
     * @param string $elementName
     *
     * @param string $expectedModelSubjectClass
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     */
    public function testStructure(
        string $filename,
        string $elementName,
        string $expectedModelSubjectClass = Form::class
    ) {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $entityBuilder = new EntityBuilder();
        $entityCollection = $entityBuilder->build(
            $this->getSchemasAndRequestBodiesCollection($filePath)
        );

        $formBuilder = new FormBuilder();
        $formCollection = $formBuilder->build(
            $this->getSchemasAndRequestBodiesCollection($filePath),
            $entityCollection
        );

        /** @var Form $structureTest */
        $structureTest = $formCollection->findElement($elementName);
        $modelBuilder = (new FormGeneratorModelBuilder('AppBundle'));

        try {
            $wrongSubjectType = false;
            $modelBuilder->buildModel(new Entity());
        } catch (SubjectTypeException $e) {
            $wrongSubjectType = true;
        }

        static::assertTrue($wrongSubjectType);

        $model = $modelBuilder->buildModel($structureTest);

        $phpGenerator = new PhpGenerator();
        $content =  $phpGenerator->generate($model);

        static::assertNotFalse(
            strpos($content, 'namespace AppBundle\Form\\' . $structureTest->getEntity()->getName())
        );
        static::assertNotFalse(
            strpos($content, 'class ' . $elementName . FormGeneratorModelBuilder::NAME_POSTFIX)
        );
        static::assertNotFalse(strpos($content, 'extends AbstractApiType'));
        static::assertNotFalse(
            strpos($content, "'data_class' => {$structureTest->getEntity()->getName()}::class,")
        );
    }

    /**
     * @return string[][]
     */
    public function structureProvider()
    {
        return [
            [
                'form-generator-model-structure.yaml',
                'UserCreate',
            ],
        ];
    }
}
