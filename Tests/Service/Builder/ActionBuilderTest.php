<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\ActionClassDefineException;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\BaseModel;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Tests\Service\Traits\ActionServiceTrait;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class ActionBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Builder
 */
class ActionBuilderTest extends TestCase
{
    use TestCaseTrait;
    use ActionServiceTrait;

    /**
     * @param string $filename
     * @param string $nodeName
     * @param string $actionServiceName
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     * @throws \Exception
     *
     * @dataProvider structureProvider
     */
    public function testStructure(string $filename, string $nodeName, string $actionServiceName)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);
        $elements = $this->getActionNode($nodeName, $filePath);
        $action = null;

        foreach ($elements as $element) {
            /** @var Action $element */
            if ($element->getServiceName() === $actionServiceName) {
                $action = $element;
                break;
            }
        }

        static::assertInstanceOf(Action::class, $action);

        switch ($actionServiceName) {
            case 'action.user.list':
                static::assertEquals(Action::DEFAULT_ACTION_LIST, $action->getClassName());
                static::assertInstanceOf(Entity::class, $action->getEntity());
                static::assertEquals('User', $action->getEntity()->getName());
                static::assertNull($action->getForm());
                static::assertEquals(BaseModel::ALLOWED_METHOD_GET, $action->getMethod());
                break;
            case 'action.user.create':
                static::assertEquals(Action::DEFAULT_ACTION_CREATE, $action->getClassName());
                static::assertInstanceOf(Entity::class, $action->getEntity());
                static::assertEquals('User', $action->getEntity()->getName());
                static::assertInstanceOf(Form::class, $action->getForm());
                static::assertEquals('UserCreate', $action->getForm()->getName());
                static::assertEquals(BaseModel::ALLOWED_METHOD_POST, $action->getMethod());
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
                'user',
                'action.user.list',
            ],
            [
                'action-structure.yaml',
                'user',
                'action.user.create',
            ],
        ];
    }

    /**
     * @param string $filename
     * @param string $nodeName
     * @param string $exception
     * @param string $message
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     *
     * @dataProvider exceptionProvider
     */
    public function testException(string $filename, string $nodeName, string $exception, string $message)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        static::expectException($exception);
        static::expectExceptionMessage($message);

        $this->getActionNode($nodeName, $filePath);
    }

    /**
     * @return string[][]
     */
    public function exceptionProvider()
    {
        return [
            [
                'action-structure-exception-action.yaml',
                'user',
                ActionClassDefineException::class,
                'Cannot define action class for "/api/users" path, "some" method and "user.list" operation ID.',
            ],
            [
                'action-structure-exception-entity.yaml',
                'user',
                EntityMissingException::class,
                'Cannot define entity class for action.',
            ],
            [
                'action-structure-exception-form.yaml',
                'user',
                FormMissingException::class,
                'Cannot define form class for action.',
            ],
        ];
    }
}
