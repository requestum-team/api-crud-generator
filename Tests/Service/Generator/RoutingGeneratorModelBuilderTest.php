<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Service\Builder\RoutingBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\YmlGenerator;
use Requestum\ApiGeneratorBundle\Tests\Service\Traits\ActionServiceTrait;
use Requestum\ApiGeneratorBundle\Tests\Service\Traits\ContentTrait;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class RoutingGeneratorModelBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Generator
 */
class RoutingGeneratorModelBuilderTest extends TestCase
{
    use TestCaseTrait;
    use ActionServiceTrait;
    use ContentTrait;

    /**
     * @param string $filename
     * @param string $nodeName
     *
     * @return string
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     * @throws \Exception
     */
    private function generateRoutingNode(string $filename, string $nodeName): string
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $actionCollection = $this->getActionCollection($filePath);

        $builder = new RoutingBuilder();
        $collection = $builder->build(FileHelper::load($filePath), $actionCollection);
        $elements = $collection->findElement($nodeName);

        $ymlGenerator = new YmlGenerator('AppBundle');

        return $ymlGenerator->generateRoutingNode($elements);
    }

    /**
     * @param string $filename
     * @param string $nodeName
     * @param string[] $expectedServicesContent
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     *
     * @dataProvider generatedContentProvider
     */
    public function testGeneratedContent(string $filename, string $nodeName, array $expectedServicesContent)
    {
        $content = $this->generateRoutingNode($filename, $nodeName);
        $this->minimizeContent($content);

        static::assertFalse(strpos($content, '/oauth/v2/token'));

        foreach ($expectedServicesContent as $expectedServiceContent) {
            $this->minimizeContent($expectedServiceContent);

            static::assertNotFalse(strpos($content, $expectedServiceContent));
        }
    }

    /**
     * @return string[][]
     */
    public function generatedContentProvider()
    {
        return [
            [
                'action-structure.yaml',
                'user',
                [
                    <<<EOF
user.list:
    path: /api/users
    methods: get
    defaults:
        _controller: 'action.user.list::executeAction'
EOF                 ,
                    <<<EOF
user.create:
    path: /api/users
    methods: post
    defaults:
        _controller: 'action.user.create::executeAction'
EOF                 ,
                    <<<EOF
user.update:
    path: '/api/users/{id}'
    methods: patch
    defaults:
        _controller: 'action.user.update::executeAction'
EOF                 ,
                    <<<EOF
user.delete:
    path: '/api/users/{id}'
    methods: delete
    defaults:
        _controller: 'action.user.delete::executeAction'
EOF                 ,
                ],
            ],
        ];
    }
}
