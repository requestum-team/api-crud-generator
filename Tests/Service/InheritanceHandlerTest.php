<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Service\InheritanceHandler;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class InheritanceHandlerTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service
 */
class InheritanceHandlerTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @param string $filename
     *
     * @dataProvider schemasProvider
     */
    public function testSchemas(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/inheritance/' . $filename);

        $inheritanceHandler = new InheritanceHandler();
        $collection = $inheritanceHandler->process(
            $this->getFileContent($filePath)
        );

        $entity = $collection['CommentEntity'];
        static::assertEquals(4, count($entity['properties']));
        static::assertEquals('integer', $entity['properties']['id']['type']);
        static::assertEquals('string', $entity['properties']['status']['type']);

        $entity = $collection['UserEntity'];
        static::assertEquals(7, count($entity['properties']));
        static::assertEquals('integer', $entity['properties']['id']['type']);
        static::assertEquals('string', $entity['properties']['role']['type']);

        $entity = $collection['MainEntity'];
        static::assertEquals(4, count($entity['properties']));
        static::assertEquals('integer', $entity['properties']['id']['type']);
        static::assertEquals('string', $entity['properties']['email']['type']);
        static::assertContains('city', $entity['required']);
        static::assertContains('email', $entity['required']);
    }

    /**
     * @param string $filename
     *
     * @dataProvider requestBodiesProvider
     */
    public function testRequestBodies(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/inheritance/' . $filename);

        $inheritanceHandler = new InheritanceHandler();
        $collection = $inheritanceHandler->process(
            $this->getFileContent($filePath)
        );

        $entity = $collection['UserCreate'];
        static::assertEquals(5, count($entity['properties']));
        static::assertEquals('string', $entity['properties']['role']['type']);
        static::assertEquals('string', $entity['properties']['plainPassword']['type']);
        static::assertEquals('string', $entity['properties']['email']['type']);
    }

    public function schemasProvider()
    {
        return [
            [
                'schemas.yaml'
            ],
        ];
    }

    public function requestBodiesProvider()
    {
        return [
            [
                'request-bodies.yaml'
            ],
        ];
    }
}
