<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\BaseModel;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Service\Builder\ActionBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class ActionBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Builder
 */
class ActionBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @param string $filename
     * @param string $elementName
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     * @throws \Exception
     *
     * @dataProvider structureProvider
     */
    public function testStructure(string $filename, string $elementName)
    {
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

        $builder = new ActionBuilder();
        $collection = $builder->build(FileHelper::load($filePath), $entityCollection, $formCollection);

        /** @var Action $element */
        $element = $collection->findElement($elementName);

        static::assertInstanceOf(Action::class, $element);

        switch ($elementName) {
            case 'action.user.list':
                static::assertEquals(Action::DEFAULT_ACTION_LIST, $element->getClassName());
                static::assertInstanceOf(Entity::class, $element->getEntity());
                static::assertEquals('User', $element->getEntity()->getName());
                static::assertNull($element->getForm());
                static::assertEquals(BaseModel::ALLOWED_METHOD_GET, $element->getMethod());
                break;
            case 'action.user.create':
                static::assertEquals(Action::DEFAULT_ACTION_CREATE, $element->getClassName());
                static::assertInstanceOf(Entity::class, $element->getEntity());
                static::assertEquals('User', $element->getEntity()->getName());
                static::assertInstanceOf(Form::class, $element->getForm());
                static::assertEquals('UserCreate', $element->getForm()->getName());
                static::assertEquals(BaseModel::ALLOWED_METHOD_POST, $element->getMethod());
                break;
        }
    }

    /**
     * @return string[][]
     */
    public function structureProvider()
    {
        return [
            [
                'action-structure.yaml',
                'action.user.list',
            ],
            [
                'action-structure.yaml',
                'action.user.create',
            ],
        ];
    }
}
