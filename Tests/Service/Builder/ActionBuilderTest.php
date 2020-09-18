<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Service\Builder\ActionBuilder;
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

        $builder = new ActionBuilder();
        $collection = $builder->build(FileHelper::load($filePath));
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
