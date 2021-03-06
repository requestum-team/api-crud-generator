<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\AccessLevelException;
use Requestum\ApiGeneratorBundle\Exception\SubjectTypeException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\Generator\AccessLevelEnum;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorMethodModel;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\EntityGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\PhpGenerator;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class EntityGeneratorModelBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Generator
 */
class EntityGeneratorModelBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @dataProvider structureProvider
     *
     * @param string $filename
     *
     * @throws AccessLevelException
     * @throws \Exception
     */
    public function testStructure(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $builder = new EntityBuilder();
        $collection = $builder->build(
            $this->getSchemasAndRequestBodiesCollection($filePath)
        );

        /** @var Entity $structureTest */
        $structureTest = $collection->findElement('StructureTest');
        $modelBuilder = new EntityGeneratorModelBuilder('AppBundle');
        $model = $modelBuilder->buildModel($structureTest);

        static::assertEquals('StructureTest', $model->getName());
        static::assertEquals('AppBundle\Entity', $model->getNameSpace());
        static::assertEquals('StructureTest.php', $model->getFilePath());
        static::assertContains('Doctrine\ORM\Mapping as ORM', $model->getUseSection());
        static::assertContains('@ORM\Table(name="`structure_test`")', $model->getAnnotations());
        static::assertContains('@ORM\Entity(repositoryClass="AppBundle\Repository\StructureTestRepository")', $model->getAnnotations());
        static::assertContains('Gedmo\Mapping\Annotation\SoftDeleteable()', $model->getAnnotations());
        static::assertContains('AbsTrait', $model->getTraits());
        static::assertContains('QweTrait', $model->getTraits());
        static::assertContains('ZaqTrait', $model->getTraits());
        static::assertContains('AppBundle\AbsTrait', $model->getUseSection());
        static::assertContains('AppBundle\QweTrait', $model->getUseSection());
        static::assertNotContains('ZaqTrait', $model->getUseSection());

        $property = $model->getPropertyByName('id');
        static::assertEquals('id', $property->getName());
        static::assertEquals(AccessLevelEnum::ACCESS_LEVEL_PROTECTED, $property->getAccessLevel());
        static::assertContains(['name' => 'ORM\Id'], $property->getAttributes());

        $property = $model->getPropertyByName('name');
        static::assertEquals('name', $property->getName());
        static::assertEquals(AccessLevelEnum::ACCESS_LEVEL_PROTECTED, $property->getAccessLevel());
        static::assertContains(['name' => 'ORM\Column(type="string", name="`name`")'], $property->getAttributes());

        $method = $model->getMethodByName('__construct');
        static::assertInstanceOf(GeneratorMethodModel::class, $method);
        static::assertEquals('__construct', $method->getName());

        $phpGenerator = new PhpGenerator();
        $content =  $phpGenerator->generate($model);

        static::assertNotFalse(strpos($content, 'AppBundle\AbsTrait'));
        static::assertNotFalse(strpos($content, 'AppBundle\QweTrait'));
        static::assertNotFalse(strpos($content, 'use Doctrine\ORM\Mapping as ORM'));
        static::assertNotFalse(strpos($content, 'use Symfony\Component\Serializer\Annotation as Serializer'));
        static::assertNotFalse(strpos($content, 'use Symfony\Component\Validator\Constraints as Assert'));
        static::assertNotFalse(strpos($content, '@Gedmo\Mapping\Annotation\SoftDeleteable()'));
        static::assertNotFalse(strpos($content, '@Assert\NotBlank(groups={"update"})'));
        static::assertNotFalse(strpos($content, '@Assert\NotBlank(groups={"create"})'));
        static::assertNotFalse(strpos($content, '@Assert\Unique'));
        static::assertNotFalse(strpos($content, '@Serializer\Groups({"default", "some_group"})'));
        static::assertNotFalse(strpos($content, '@Serializer\Groups({"default"})'));
        static::assertNotFalse(strpos($content, '@Reference()'));
        static::assertNotFalse(strpos($content, '@Assert\NotBlank'));
        static::assertNotFalse(strpos($content, '@Assert\Email'));
        static::assertNotFalse(strpos($content, '@Assert\Regex("^\d{3}-\d{2}-\d{4}$")'));
        static::assertNotFalse(strpos($content, '@Assert\Range(min = 5, max = 10)'));
        static::assertNotFalse(strpos($content, '@Assert\GreaterThanOrEqual(1)'));
        static::assertNotFalse(strpos($content, '@Assert\LessThanOrEqual(10000)'));
        static::assertNotFalse(strpos($content, '@Assert\Count(min=1, max=10)'));
    }

    /**
     * @return string[][]
     */
    public function structureProvider()
    {
        return [
            [
                'entity-generator-model-structure.yaml'
            ],
        ];
    }

    /**
     * @param object $subject
     * @param string $exception
     * @param string $message
     *
     * @throws AccessLevelException
     * @dataProvider modelTypeBuilderExceptionProvider
     */
    public function testModelBuilderTypeException(object $subject, string $exception, string $message)
    {
        static::expectException($exception);
        static::expectExceptionMessage($message);

        $modelBuilder = new EntityGeneratorModelBuilder('AppBundle');
        $modelBuilder->buildModel($subject);
    }

    /**
     * @return string[][]
     */
    public function modelTypeBuilderExceptionProvider()
    {
        return [
            [
                new Form(),
                SubjectTypeException::class,
                sprintf(
                    'Wrong subject type: %s. Expected class type: %s.',
                    Form::class,
                    Entity::class
                )
            ],
        ];
    }
}
