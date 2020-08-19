<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class FormBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Builder
 */
class FormBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @param string $filename
     * @dataProvider structureProvider
     * @throws \Exception
     */
    public function testStructure(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $entityBuilder = new EntityBuilder();
        $entityCollection = $entityBuilder->build(
            $this->getFileContent($filePath)
        );

        $formBuilder = new FormBuilder();
        $collection = $formBuilder->build(
            $this->getFileContent($filePath),
            $entityCollection
        );

        $elements = $collection->getElements();
        static::assertEquals(2, count($elements));

        /** @var Form $structureTest */
        $structureTest = $collection->findElement('UserCreate');
        static::assertInstanceOf(Form::class, $structureTest);
        static::assertEquals('UserCreate', $structureTest->getName());
        static::assertEquals(8, count($structureTest->getProperties()));

        $property = $structureTest->getPropertyByNameCamelCase('firstName');
        static::assertEquals('firstName', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertTrue($property->isRequired());

        $property = $structureTest->getPropertyByNameCamelCase('email');
        static::assertEquals('email', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertEquals('email', $property->getFormat());
        static::assertTrue($property->isRequired());

        $property = $structureTest->getPropertyByNameCamelCase('age');
        static::assertEquals('age', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_INTEGER, $property->getType());

        $property = $structureTest->getPropertyByNameCamelCase('type');
        static::assertEquals('type', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertIsArray($property->getEnum());
        static::assertContains('user', $property->getEnum());
        static::assertContains('manager', $property->getEnum());
        static::assertContains('admin', $property->getEnum());

        $property = $structureTest->getPropertyByNameCamelCase('shopId');
        static::assertEquals('shopId', $property->getNameCamelCase());
        static::assertTrue($property->isEntity());
        static::assertInstanceOf(Entity::class, $property->getReferencedObject());
        static::assertEquals('Shop', $property->getReferencedObject()->getName());

        $property = $structureTest->getPropertyByNameCamelCase('addresses');
        static::assertEquals('addresses', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_ARRAY, $property->getType());
        static::assertTrue($property->isForm());
        static::assertInstanceOf(Form::class, $property->getReferencedObject());
        static::assertEquals('AddressInput', $property->getReferencedObject()->getName());
        static::assertEquals(1, count($property->getReferencedObject()->getProperties()));
    }

    /**
     * @param string $exception
     * @param string $filename
     * @param string $message
     *
     * @dataProvider exceptionProvider
     */
    public function testException(string $exception, string $filename, string $message)
    {
        static::expectException($exception);
        static::expectExceptionMessage($message);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $entityBuilder = new EntityBuilder();
        $entityCollection = $entityBuilder->build(
            $this->getFileContent($filePath)
        );

        $formBuilder = new FormBuilder();
        $collection = $formBuilder->build(
            $this->getFileContent($filePath),
            $entityCollection
        );
    }

    public function structureProvider()
    {
        return [
            [
                'form-structure.yaml'
            ],
        ];
    }

    public function exceptionProvider()
    {
        return [
            [
                CollectionException::class,
                'form-entity-collection-missing.yaml',
                'Required the entity collection. Form UserCreate has as a dependency an entity UserEntity',
            ],
            [
                CollectionException::class,
                'form-entity-collection-missing-two.yaml',
                'Required the entity collection. Form ItemCreate has as a dependency an entity ShopEntity',
            ],
            [
                EntityMissingException::class,
                'form-entity-missing.yaml',
                'Entity Some is missing in the entity collection',
            ],
            [
                CollectionException::class,
                'form-entity-missing-required-collection.yaml',
                'Required the entity collection. Form UserCreate has as a dependency an entity MissingEntity',
            ],
            [
                EntityMissingException::class,
                'form-referenced-entity-missing.yaml',
                'Entity Missing is missing in the entity collection',
            ],
            [
                FormMissingException::class,
                'form-missing.yaml',
                'Form UserCreate has a relation with missing form AddressInput',
            ]
        ];
    }
}
