<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Model\BaseModel;
use Requestum\ApiGeneratorBundle\Model\Routing;
use Requestum\ApiGeneratorBundle\Service\Builder\RoutingBuilder;
use Requestum\ApiGeneratorBundle\Tests\Service\Traits\ActionServiceTrait;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class RoutingBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Builder
 */
class RoutingBuilderTest extends TestCase
{
    use TestCaseTrait;
    use ActionServiceTrait;

    /**
     * @param string $filename
     * @param string $nodeName
     * @param string $routingName
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     * @throws \Exception
     *
     * @dataProvider structureProvider
     */
    public function testStructure(string $filename, string $nodeName, string $routingName)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);
        $actionCollection = $this->getActionCollection($filePath);

        static::assertFalse($actionCollection->isEmpty());

        $builder = new RoutingBuilder();
        $collection = $builder->build(FileHelper::load($filePath), $actionCollection);
        $elements = $collection->findElement($nodeName);

        foreach ($elements as $element) {
            /** @var Routing $element */
            if ($element->getServiceName() === $routingName) {
                $routing = $element;
                break;
            }
        }

        static::assertInstanceOf(Routing::class, $routing);
        static::assertEquals('action.' . $routingName, $routing->getParent());

        switch ($routingName) {
            case 'user.list':
                static::assertEquals('/api/users', $routing->getPath());
                static::assertEquals(BaseModel::ALLOWED_METHOD_GET, $routing->getMethod());
                break;
            case 'user.create':
                static::assertEquals('/api/users', $routing->getPath());
                static::assertEquals(BaseModel::ALLOWED_METHOD_POST, $routing->getMethod());
                break;
            case 'user.update':
                static::assertEquals('/api/users/{id}', $routing->getPath());
                static::assertEquals(BaseModel::ALLOWED_METHOD_PATCH, $routing->getMethod());
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
                'routing-structure.yaml',
                'user',
                'user.list',
            ],
            [
                'routing-structure.yaml',
                'user',
                'user.create',
            ],
            [
                'routing-structure.yaml',
                'user',
                'user.update',
            ],
        ];
    }
}
