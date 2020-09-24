<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
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
     *
     * @dataProvider structureProvider
     *
     * @throws \Exception
     */
    public function testStructure(string $filename)
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
    }

    /**
     * @return string[][]
     */
    public function structureProvider()
    {
        return [
            [
                'action-structure.yaml',
            ],
        ];
    }
}
