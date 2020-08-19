<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\PrimaryException;
use Requestum\ApiGeneratorBundle\Exception\PropertyTypeException;
use Requestum\ApiGeneratorBundle\Exception\ReferencedColumnException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
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
     * @dataProvider structureProvider
     */
    public function testStructure(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        $elements = $collection->getElements();
        static::assertEquals(5, count($elements));

        /** @var Entity $structureTest */
        $structureTest = $collection->findElement('StructureTest');
        static::assertInstanceOf(Entity::class, $structureTest);
        static::assertEquals('StructureTest', $structureTest->getName());
        static::assertEquals('structure_test', $structureTest->getTableName());
        static::assertEquals('StructureTestEntity', $structureTest->getOriginObjectName());
        static::assertEquals(12, count($structureTest->getProperties()));
        static::assertContains('AppBundle\AbsTrait', $structureTest->getTraits());
        static::assertContains('AppBundle\QweTrait', $structureTest->getTraits());

        $property = $structureTest->getPropertyByName('id');
        static::assertEquals('id', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_INTEGER, $property->getType());
        static::assertTrue($property->isPrimary());

        $property = $structureTest->getPropertyByName('name');
        static::assertEquals('name', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertTrue($property->isRequired());

        $property = $structureTest->getPropertyByName('email');
        static::assertEquals('email', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertEquals('email', $property->getFormat());
        static::assertTrue($property->isNullable());

        $property = $structureTest->getPropertyByName('slug');
        static::assertEquals('slug', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertEquals(5, $property->getMinLength());
        static::assertEquals(10, $property->getMaxLength());

        $property = $structureTest->getPropertyByName('ssn');
        static::assertEquals('ssn', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertEquals('^\d{3}-\d{2}-\d{4}$', $property->getPattern());

        $property = $structureTest->getPropertyByName('amount');
        static::assertEquals('amount', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_INTEGER, $property->getType());
        static::assertEquals(5, $property->getMinimum());
        static::assertEquals(10, $property->getMaximum());

        $property = $structureTest->getPropertyByName('postCount');
        static::assertEquals('postCount', $property->getName());
        static::assertEquals('post_count', $property->getDatabasePropertyName());
        static::assertEquals(PropertyTypeEnum::TYPE_INTEGER, $property->getType());
        static::assertEquals('int32', $property->getFormat());

        $property = $structureTest->getPropertyByName('price');
        static::assertEquals('price', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_NUMBER, $property->getType());
        static::assertEquals('double', $property->getFormat());

        $property = $structureTest->getPropertyByName('status');
        static::assertEquals('status', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_STRING, $property->getType());
        static::assertIsArray($property->getEnum());
        static::assertContains('new', $property->getEnum());
        static::assertContains('draft', $property->getEnum());
        static::assertContains('in_progress', $property->getEnum());

        $property = $structureTest->getPropertyByName('arrayField');
        static::assertEquals('arrayField', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_ARRAY, $property->getType());
        static::assertEquals(PropertyTypeEnum::TYPE_INTEGER, $property->getItemsType());
        static::assertEquals(1, $property->getMinItems());
        static::assertEquals(10, $property->getMaxItems());

        $property = $structureTest->getPropertyByName('comments');
        static::assertEquals('comments', $property->getName());
        static::assertEquals(PropertyTypeEnum::TYPE_ARRAY, $property->getType());
        static::assertEquals('CommentEntity', $property->getReferencedLink());

        $property = $structureTest->getPropertyByName('postId');
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

        $builder = new EntityBuilder();
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
        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $commentEntity */
        $commentEntity = $collection->findElement('Comment');
        static::assertInstanceOf(Entity::class, $commentEntity);

        /** @var Entity $postEntity */
        $postEntity = $collection->findElement('Post');
        static::assertInstanceOf(Entity::class, $postEntity);

        $postId = $commentEntity->getPropertyByName('postId');
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
        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $commentEntity */
        $commentEntity = $collection->findElement('Comment');
        static::assertInstanceOf(Entity::class, $commentEntity);

        /** @var Entity $postEntity */
        $postEntity = $collection->findElement('Post');
        static::assertInstanceOf(Entity::class, $postEntity);

        $postId = $commentEntity->getPropertyByName('postId');
        static::assertTrue($postId->isManyToOne());
        static::assertTrue($postId->isForeignKey());
        static::assertContains($postId->getReferencedColumn(), $postEntity->getPrimaryColumns());

        $comments = $postEntity->getPropertyByName('comments');
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
        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $payInfoEntity */
        $payInfoEntity = $collection->findElement('PayInfo');
        static::assertInstanceOf(Entity::class, $payInfoEntity);

        /** @var Entity $employeeEntity */
        $employeeEntity = $collection->findElement('Employee');
        static::assertInstanceOf(Entity::class, $employeeEntity);

        $employee = $payInfoEntity->getPropertyByName('employee');
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
        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $payInfoEntity */
        $payInfoEntity = $collection->findElement('PayInfo');
        static::assertInstanceOf(Entity::class, $payInfoEntity);

        /** @var Entity $employeeEntity */
        $employeeEntity = $collection->findElement('Employee');
        static::assertInstanceOf(Entity::class, $employeeEntity);

        $employee = $payInfoEntity->getPropertyByName('employee');
        static::assertTrue($employee->isOneToOne());
        static::assertTrue($employee->isForeignKey());
        static::assertContains($employee->getReferencedColumn(), $employeeEntity->getPrimaryColumns());

        $payInfo = $employeeEntity->getPropertyByName('payInfo');
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
        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $commentEntity */
        $commentEntity = $collection->findElement('Comment');
        static::assertInstanceOf(Entity::class, $commentEntity);

        /** @var Entity $postEntity */
        $postEntity = $collection->findElement('Post');
        static::assertInstanceOf(Entity::class, $postEntity);

        $postId = $commentEntity->getPropertyByName('postId');
        static::assertTrue($postId->isManyToOne());
        static::assertTrue($postId->isForeignKey());
        static::assertContains($postId->getReferencedColumn(), $postEntity->getPrimaryColumns());

        $comments = $postEntity->getPropertyByName('comments');
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
        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getFileContent($filePath)
        );

        /** @var Entity $itemEntity */
        $itemEntity = $collection->findElement('Item');
        static::assertInstanceOf(Entity::class, $itemEntity);

        /** @var Entity $categoryEntity */
        $categoryEntity = $collection->findElement('Category');
        static::assertInstanceOf(Entity::class, $categoryEntity);

        $categories = $itemEntity->getPropertyByName('categories');
        static::assertTrue($categories->isManyToMany());
        static::assertContains($categories->getReferencedColumn(), $categoryEntity->getPrimaryColumns());

        $items = $categoryEntity->getPropertyByName('items');
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
                PropertyTypeException::class,
                'array-max-items.yaml',
                'Max items applies only for type array. Use maxLength for type string or maximum for types integer and number'
            ],
            [
                PropertyTypeException::class,
                'array-min-items.yaml',
                'Min items applies only for type array. Use minLength for type string or minimum for types integer and number'
            ],
            [
                PropertyTypeException::class,
                'integer-maximum.yaml',
                'Maximum applies only for integer or number types. Use maxLength for type string or maxItems for type array'
            ],
            [
                PropertyTypeException::class,
                'integer-minimum.yaml',
                'Minimum applies only for integer or number types. Use minLength for type string or mimItems type array'
            ],
            [
                PropertyTypeException::class,
                'string-max-length.yaml',
                'Max length applies only for type string. Use maximum for types integer and number or maxItems for type array'
            ],
            [
                PropertyTypeException::class,
                'string-min-length.yaml',
                'Min length applies only for type string. Use minimum for types integer and number or minItems for type array'
            ],
            [
                PropertyTypeException::class,
                'string-pattern.yaml',
                'Pattern applies only for type string.'
            ],
            [
                PrimaryException::class,
                'primary-key-zero.yaml',
                'The entity "Post" doesn\'t have any primary key'
            ],
            [
                PrimaryException::class,
                'primary-key-two.yaml',
                'The entity "Post" has more than one primary key'
            ],
            [
                ReferencedColumnException::class,
                'backref-absent.yaml',
                'Couldn\'t find a referenced column payInfo in an entity Employee'
            ],
            [
                ReferencedColumnException::class,
                'backref-two.yaml',
                'The back referenced column has to be only from one side'
            ],
            [
                ReferencedColumnException::class,
                'many-to-one-bidirectional-type.yaml',
                'The column comments has to be type array, type integer is given'
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
