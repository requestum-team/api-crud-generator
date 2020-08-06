<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Config;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class EntityBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Builder
 */
class EntityBuilderTest extends TestCase
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

        $builder = new EntityBuilder($this->config);
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        $elements = $collection->getElements();
        static::assertEquals(3, count($elements));

        /** @var Entity $structureTest */
        $structureTest = $collection->findElement('StructureTest');
        static::assertInstanceOf(Entity::class, $structureTest);
        static::assertEquals('StructureTest', $structureTest->getName());
        static::assertEquals('structure_test', $structureTest->getTableName());
        static::assertEquals('StructureTestEntity', $structureTest->getOriginObjectName());
        static::assertEquals(12, count($structureTest->getProperties()));

        $property = $structureTest->getProperyByName('id');
        static::assertEquals('id', $property->getName());
        static::assertEquals(EntityProperty::TYPE_INTEGER, $property->getType());
        static::assertTrue($property->isPrimary());

        $property = $structureTest->getProperyByName('name');
        static::assertEquals('name', $property->getName());
        static::assertEquals(EntityProperty::TYPE_STRING, $property->getType());
        static::assertTrue($property->isRequired());

        $property = $structureTest->getProperyByName('email');
        static::assertEquals('email', $property->getName());
        static::assertEquals(EntityProperty::TYPE_STRING, $property->getType());
        static::assertEquals('email', $property->getFormat());
        static::assertTrue($property->isNullable());

        $property = $structureTest->getProperyByName('slug');
        static::assertEquals('slug', $property->getName());
        static::assertEquals(EntityProperty::TYPE_STRING, $property->getType());
        static::assertEquals(5, $property->getMinLength());
        static::assertEquals(10, $property->getMaxLength());

        $property = $structureTest->getProperyByName('ssn');
        static::assertEquals('ssn', $property->getName());
        static::assertEquals(EntityProperty::TYPE_STRING, $property->getType());
        static::assertEquals('^\d{3}-\d{2}-\d{4}$', $property->getPattern());

        $property = $structureTest->getProperyByName('amount');
        static::assertEquals('amount', $property->getName());
        static::assertEquals(EntityProperty::TYPE_INTEGER, $property->getType());
        static::assertEquals(5, $property->getMinimum());
        static::assertEquals(10, $property->getMaximum());

        $property = $structureTest->getProperyByName('postCount');
        static::assertEquals('postCount', $property->getName());
        static::assertEquals('post_count', $property->getDatabasePropertyName());
        static::assertEquals(EntityProperty::TYPE_INTEGER, $property->getType());
        static::assertEquals('int32', $property->getFormat());

        $property = $structureTest->getProperyByName('price');
        static::assertEquals('price', $property->getName());
        static::assertEquals(EntityProperty::TYPE_NUMBER, $property->getType());
        static::assertEquals('double', $property->getFormat());

        $property = $structureTest->getProperyByName('status');
        static::assertEquals('status', $property->getName());
        static::assertEquals(EntityProperty::TYPE_STRING, $property->getType());
        static::assertIsArray($property->getEnum());
        static::assertContains('new', $property->getEnum());
        static::assertContains('draft', $property->getEnum());
        static::assertContains('in_progress', $property->getEnum());

        $property = $structureTest->getProperyByName('arrayField');
        static::assertEquals('arrayField', $property->getName());
        static::assertEquals(EntityProperty::TYPE_ARRAY, $property->getType());
        static::assertEquals(EntityProperty::TYPE_INTEGER, $property->getItemsType());
        static::assertEquals(1, $property->getMinItems());
        static::assertEquals(10, $property->getMaxItems());

        $property = $structureTest->getProperyByName('comments');
        static::assertEquals('comments', $property->getName());
        static::assertEquals(EntityProperty::TYPE_ARRAY, $property->getType());
        static::assertEquals('CommentEntity', $property->getReferencedLink());

        $property = $structureTest->getProperyByName('postId');
        static::assertEquals('postId', $property->getName());
        static::assertNull($property->getType());
        static::assertEquals('PostEntity', $property->getReferencedLink());

        /** @var Entity $primaryKeyEntity */
        $primaryKeyEntity = $collection->findElement('PrimaryKey');
        static::assertCount(2, $primaryKeyEntity->getPrimaryColumns());

        /** @var Entity $nonePrimaryKeyEntity */
        $nonePrimaryKeyEntity = $collection->findElement('NonePrimaryKey');
        static::assertCount(0, $nonePrimaryKeyEntity->getPrimaryColumns());
    }

    /**
     * @dataProvider exceptionProvider
     */
    public function testException(string $filename)
    {
        static::expectException(\Exception::class);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $builder = new EntityBuilder($this->config);
        $builder->build(
            $this->getFileContent($filePath)
        );
    }

    /**
     * @dataProvider primaryKeyExceptionProvider
     */
    public function testPrimaryKeyException(string $filename)
    {
        static::expectException(\Exception::class);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $builder = new EntityBuilder($this->config);
        $builder->build(
            $this->getFileContent($filePath)
        );
    }

    /**
     * @dataProvider backrefExceptionProvider
     */
    public function testBackrefException(string $filename)
    {
        static::expectException(\Exception::class);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $builder = new EntityBuilder($this->config);
        $builder->build(
            $this->getFileContent($filePath)
        );
    }

    /**
     * @dataProvider manyToOneBidirectionalTypeExceptionProvider
     */
    public function testManyToOneBidirectionalTypeException(string $filename)
    {
        static::expectException(\Exception::class);

        $filePath = realpath(__DIR__ . '/providers/exception/' . $filename);

        $builder = new EntityBuilder($this->config);
        $builder->build(
            $this->getFileContent($filePath)
        );
    }

    /**
     * @dataProvider manyToOneUnidirectionalProvider
     */
    public function testManyToOneUnidirectional(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/relations/' . $filename);
        $builder = new EntityBuilder($this->config);
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $commentEntity */
        $commentEntity = $collection->findElement('Comment');
        static::assertInstanceOf(Entity::class, $commentEntity);

        /** @var Entity $postEntity */
        $postEntity = $collection->findElement('Post');
        static::assertInstanceOf(Entity::class, $postEntity);

        $postId = $commentEntity->getProperyByName('postId');
        static::assertTrue($postId->isManyToOne());
        static::assertTrue($postId->isForeignKey());
        static::assertContains($postId->getReferencedColumn(), $postEntity->getPrimaryColumns());
    }

    /**
     * @dataProvider manyToOneBidirectionalProvider
     */
    public function testManyToOneBidirectional(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/relations/' . $filename);
        $builder = new EntityBuilder($this->config);
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $commentEntity */
        $commentEntity = $collection->findElement('Comment');
        static::assertInstanceOf(Entity::class, $commentEntity);

        /** @var Entity $postEntity */
        $postEntity = $collection->findElement('Post');
        static::assertInstanceOf(Entity::class, $postEntity);

        $postId = $commentEntity->getProperyByName('postId');
        static::assertTrue($postId->isManyToOne());
        static::assertTrue($postId->isForeignKey());
        static::assertContains($postId->getReferencedColumn(), $postEntity->getPrimaryColumns());

        $comments = $postEntity->getProperyByName('comments');
        static::assertTrue($comments->isOneToMany());
        static::assertTrue($comments->isBackRefColumn());
        static::assertEquals($comments->getReferencedColumn(), $postId);
    }

    /**
     * @dataProvider oneToOneUnidirectionalProvider
     */
    public function testOneToOneUnidirectional(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/relations/' . $filename);
        $builder = new EntityBuilder($this->config);
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $payInfoEntity */
        $payInfoEntity = $collection->findElement('PayInfo');
        static::assertInstanceOf(Entity::class, $payInfoEntity);

        /** @var Entity $employeeEntity */
        $employeeEntity = $collection->findElement('Employee');
        static::assertInstanceOf(Entity::class, $employeeEntity);

        $employee = $payInfoEntity->getProperyByName('employee');
        static::assertTrue($employee->isOneToOne());
        static::assertTrue($employee->isForeignKey());
        static::assertContains($employee->getReferencedColumn(), $employeeEntity->getPrimaryColumns());
    }

    /**
     * @dataProvider oneToOneBidirectionalProvider
     */
    public function testOneToOneBidirectional(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/relations/' . $filename);
        $builder = new EntityBuilder($this->config);
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $payInfoEntity */
        $payInfoEntity = $collection->findElement('PayInfo');
        static::assertInstanceOf(Entity::class, $payInfoEntity);

        /** @var Entity $employeeEntity */
        $employeeEntity = $collection->findElement('Employee');
        static::assertInstanceOf(Entity::class, $employeeEntity);

        $employee = $payInfoEntity->getProperyByName('employee');
        static::assertTrue($employee->isOneToOne());
        static::assertTrue($employee->isForeignKey());
        static::assertContains($employee->getReferencedColumn(), $employeeEntity->getPrimaryColumns());

        $payInfo = $employeeEntity->getProperyByName('payInfo');
        static::assertTrue($payInfo->isOneToOne());
        static::assertTrue($payInfo->isBackRefColumn());
        static::assertEquals($payInfo->getReferencedColumn(), $employee);
    }

    /**
     * @dataProvider oneToMabyBidirectionalProvider
     */
    public function testOneToManyBidirectional(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/relations/' . $filename);
        $builder = new EntityBuilder($this->config);
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $commentEntity */
        $commentEntity = $collection->findElement('Comment');
        static::assertInstanceOf(Entity::class, $commentEntity);

        /** @var Entity $postEntity */
        $postEntity = $collection->findElement('Post');
        static::assertInstanceOf(Entity::class, $postEntity);

        $postId = $commentEntity->getProperyByName('postId');
        static::assertTrue($postId->isManyToOne());
        static::assertTrue($postId->isForeignKey());
        static::assertContains($postId->getReferencedColumn(), $postEntity->getPrimaryColumns());

        $comments = $postEntity->getProperyByName('comments');
        static::assertTrue($comments->isOneToMany());
        static::assertTrue($comments->isBackRefColumn());
        static::assertEquals($comments->getReferencedColumn(), $postId);
    }

    /**
     * @dataProvider manyToManyProvider
     */
    public function testManyToMany(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/relations/' . $filename);
        $builder = new EntityBuilder($this->config);
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $itemEntity */
        $itemEntity = $collection->findElement('Item');
        static::assertInstanceOf(Entity::class, $itemEntity);

        /** @var Entity $categoryEntity */
        $categoryEntity = $collection->findElement('Category');
        static::assertInstanceOf(Entity::class, $categoryEntity);

        $categories = $itemEntity->getProperyByName('categories');
        static::assertTrue($categories->isManyToMany());
        static::assertContains($categories->getReferencedColumn(), $categoryEntity->getPrimaryColumns());

        $items = $categoryEntity->getProperyByName('items');
        static::assertTrue($items->isManyToMany());
        static::assertContains($items->getReferencedColumn(), $itemEntity->getPrimaryColumns());
    }

    public function structureProvider()
    {
        return [
            [
                'entity-structure.yaml'
            ],
        ];
    }

    public function exceptionProvider()
    {
        return [
            [
                'array-max-items.yaml',
                'array-min-items.yaml',
                'integer-maximum.yaml',
                'integer-minimum.yaml',
                'string-max-length.yaml',
                'string-min-length.yaml',
                'string-pattern.yaml',
            ],
        ];
    }

    public function primaryKeyExceptionProvider()
    {
        return [
            [
                'primary-key-zero.yaml',
                'primary-key-two.yaml',
            ],
        ];
    }

    public function backrefExceptionProvider()
    {
        return [
            [
                'backref-absent.yaml',
                'backref-two.yaml',
            ],
        ];
    }

    public function manyToOneBidirectionalTypeExceptionProvider()
    {
        return [
            [
                'many-to-one-bidirectional-type.yaml',
            ],
        ];
    }

    public function manyToOneUnidirectionalProvider()
    {
        return [
            [
                'many-to-one-unidirectional.yaml',
            ],
        ];
    }

    public function oneToMabyBidirectionalProvider()
    {
        return [
            [
                'one-to-many-bidirectional.yaml',
            ],
        ];
    }

    public function manyToOneBidirectionalProvider()
    {
        return [
            [
                'many-to-one-bidirectional.yaml',
            ],
        ];
    }

    public function oneToOneUnidirectionalProvider()
    {
        return [
            [
                'one-to-one-unidirectional.yaml',
                'one-to-one-unidirectional-order.yaml',
            ],
        ];
    }

    public function oneToOneBidirectionalProvider()
    {
        return [
            [
                'one-to-one-bidirectional-backref.yaml',
            ],
        ];
    }

    public function manyToManyProvider()
    {
        return [
            [
                'many-to-many.yaml',
            ],
        ];
    }
}
