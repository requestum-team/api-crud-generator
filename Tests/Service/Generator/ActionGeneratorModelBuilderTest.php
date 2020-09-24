<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Service\Generator\YmlGenerator;
use Requestum\ApiGeneratorBundle\Tests\Service\Traits\ActionServiceTrait;
use Requestum\ApiGeneratorBundle\Tests\Service\Traits\ContentTrait;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class ActionGeneratorModelBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Generator
 */
class ActionGeneratorModelBuilderTest extends TestCase
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
    private function generateActionNode(string $filename, string $nodeName): string
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);
        $elements = $this->getActionNode($nodeName, $filePath);

        $ymlGenerator = new YmlGenerator('AppBundle');

        return $ymlGenerator->generateActionNode($elements);
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
        $content = $this->generateActionNode($filename, $nodeName);
        $this->minimizeContent($content);

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
                    'services:',
                    <<<EOF
action.user.list:
    class: Requestum\ApiBundle\Action\ListAction
    arguments: [AppBundle\Entity\User]
    tags: [controller.service_arguments]
EOF                 ,
                    <<<EOF
action.user.create:
    class: Requestum\ApiBundle\Action\CreateAction
    arguments: [AppBundle\Entity\User, AppBundle\Form\User\UserCreate]
    tags: [controller.service_arguments]
EOF                 ,
                ],
            ],
        ];
    }
}
