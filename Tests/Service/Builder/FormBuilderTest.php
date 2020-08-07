<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Service\Config;
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
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $configPath = realpath(__DIR__ .  '/../../../config.example.yml');
        $this->config = new Config($configPath);
    }

    /**
     * @dataProvider structureProvider
     */
    public function testStructure(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $entityBuilder = new EntityBuilder($this->config);
        $entityCollection = $entityBuilder->build(
            $this->getFileContent($filePath)
        );

        $formBuilder = new FormBuilder($this->config);
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

        $property = $structureTest->getProperyByNameCamelCase('firstName');
        static::assertEquals('firstName', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertTrue($property->isRequired());

        $property = $structureTest->getProperyByNameCamelCase('email');
        static::assertEquals('email', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertEquals('email', $property->getFormat());
        static::assertTrue($property->isRequired());

        $property = $structureTest->getProperyByNameCamelCase('age');
        static::assertEquals('age', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_INTEGER, $property->getType());

        $property = $structureTest->getProperyByNameCamelCase('type');
        static::assertEquals('type', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertIsArray($property->getEnum());
        static::assertContains('user', $property->getEnum());
        static::assertContains('manager', $property->getEnum());
        static::assertContains('admin', $property->getEnum());

        $property = $structureTest->getProperyByNameCamelCase('shopId');
        static::assertEquals('shopId', $property->getNameCamelCase());
        static::assertTrue($property->isEntity());
        static::assertInstanceOf(Entity::class, $property->getReferencedObject());
        static::assertEquals('Shop', $property->getReferencedObject()->getName());

        $property = $structureTest->getProperyByNameCamelCase('addresses');
        static::assertEquals('addresses', $property->getNameCamelCase());
        static::assertEquals(PropertyTypeEnum::TYPE_ARRAY, $property->getType());
        static::assertTrue($property->isForm());
        static::assertInstanceOf(Form::class, $property->getReferencedObject());
        static::assertEquals('AddressInput', $property->getReferencedObject()->getName());
        static::assertEquals(1, count($property->getReferencedObject()->getProperties()));
    }

    /**
     * @dataProvider entityCollectionExceptionProvider
     */
    public function testEntityCollectionException(string $filename, string $message)
    {
        static::expectException(CollectionException::class);
        static::expectExceptionMessage($message);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $entityBuilder = new EntityBuilder($this->config);
        $entityCollection = $entityBuilder->build(
            $this->getFileContent($filePath)
        );

        $formBuilder = new FormBuilder($this->config);
        $collection = $formBuilder->build(
            $this->getFileContent($filePath),
            $entityCollection
        );
    }

    /**
     * @dataProvider formMissingExceptionProvider
     */
    public function testFormMissingException(string $filename, string $message)
    {
        static::expectException(FormMissingException::class);
        static::expectExceptionMessage($message);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $entityBuilder = new EntityBuilder($this->config);
        $entityCollection = $entityBuilder->build(
            $this->getFileContent($filePath)
        );

        $formBuilder = new FormBuilder($this->config);
        $collection = $formBuilder->build(
            $this->getFileContent($filePath),
            $entityCollection
        );
    }

    /**
     * @dataProvider entityMissingExceptionProvider
     */
    public function testEntityMissingException(string $exception, string $filename, string $message)
    {
        static::expectException($exception);
        static::expectExceptionMessage($message);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $entityBuilder = new EntityBuilder($this->config);
        $entityCollection = $entityBuilder->build(
            $this->getFileContent($filePath)
        );

        $formBuilder = new FormBuilder($this->config);
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

    public function entityCollectionExceptionProvider()
    {
        return [
            [
                'form-entity-collection-missing.yaml',
                'Required the entity collection. Form UserCreate has as a dependency an entity UserEntity',
            ],
            [
                'form-entity-collection-missing-two.yaml',
                'Required the entity collection. Form ItemCreate has as a dependency an entity ShopEntity',
            ],
        ];
    }

    public function entityMissingExceptionProvider()
    {
        return [
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
        ];
    }

    public function formMissingExceptionProvider()
    {
        return [
            [
                'form-missing.yaml',
                'Form UserCreate has a relation with missing form AddressInput',
            ],
        ];
    }
}
