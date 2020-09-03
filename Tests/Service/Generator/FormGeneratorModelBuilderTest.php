<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Generator;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Exception\SubjectTypeException;
use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\FormGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\PhpGenerator;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class FormGeneratorModelBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Generator
 */
class FormGeneratorModelBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @param string $filename
     *
     * @return string
     */
    private function getProviderFilePath(string $filename): string
    {
        return realpath(__DIR__ . '/providers/' . $filename);
    }

    /**
     * @param string $filename
     *
     * @return BaseAbstractCollection
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     */
    private function getFormCollection(string $filename): BaseAbstractCollection
    {
        return (new FormBuilder())->build(
            $this->getSchemasAndRequestBodiesCollection($this->getProviderFilePath($filename)),
            (new EntityBuilder())->build(
                $this->getSchemasAndRequestBodiesCollection($this->getProviderFilePath($filename))
            )
        );
    }

    /**
     * @param object $subject
     *
     * @return string
     */
    private function generateModel(object $subject): string
    {
        $modelBuilder = (new FormGeneratorModelBuilder('AppBundle'));
        $model = $modelBuilder->buildModel($subject);
        $phpGenerator = new PhpGenerator();

        return $phpGenerator->generate($model);
    }

    /**
     * @dataProvider structureProvider
     *
     * @param string $filename
     * @param string $elementName
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     */
    public function testStructure(string $filename, string $elementName)
    {
        $formCollection = $this->getFormCollection($filename);
        /** @var Form $form */
        $form = $formCollection->findElement($elementName);
        $content = $this->generateModel($form);

        static::assertNotFalse(
            strpos($content, 'namespace AppBundle\Form\\' . $form->getEntity()->getName())
        );
        static::assertNotFalse(
            strpos($content, 'class ' . $elementName . FormGeneratorModelBuilder::NAME_POSTFIX)
        );
        static::assertNotFalse(strpos($content, 'extends AbstractApiType'));
        static::assertNotFalse(
            strpos($content, "'data_class' => {$form->getEntity()->getName()}::class,")
        );
    }

    /**
     * @return string[][]
     */
    public function structureProvider()
    {
        return [
            [
                'form-generator-model-structure.yaml',
                'UserCreate',
            ],
        ];
    }

    /**
     * @param object $subject
     * @param string $exception
     * @param string $message
     *
     * @dataProvider modelTypeBuilderExceptionProvider
     */
    public function testModelBuilderTypeException(object $subject, string $exception, string $message)
    {
        static::expectException($exception);
        static::expectExceptionMessage($message);

        $modelBuilder = new FormGeneratorModelBuilder('AppBundle');
        $modelBuilder->buildModel($subject);
    }

    /**
     * @return string[][]
     */
    public function modelTypeBuilderExceptionProvider()
    {
        return [
            [
                new Entity(),
                SubjectTypeException::class,
                sprintf(
                    'Wrong subject type: %s. Expected class type: %s.',
                    Entity::class,
                    Form::class
                )
            ],
        ];
    }

    /**
     * @param string $filename
     * @param string $elementName
     * @param array $propertiesExpectedContent
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     *
     * @dataProvider propertiesProvider
     */
    public function testProperties(string $filename, string $elementName, array $propertiesExpectedContent)
    {
        $replace = ["/[\n\r\s]+/u", ' '];
        $content = $this->generateModel($this->getFormCollection($filename)->findElement($elementName));
        $content = preg_replace($replace[0], $replace[1], $content);

        foreach ($propertiesExpectedContent as $propertyExpectedContent) {
            $useSections =
                is_array($propertyExpectedContent[0])
                    ? $propertyExpectedContent[0]
                    : [$propertyExpectedContent[0]]
            ;

            foreach ($useSections as $useSection) {
                static::assertNotFalse(strpos($content, 'use ' . $useSection));
            }

            static::assertNotFalse(
                strpos($content, preg_replace($replace[0], $replace[1], $propertyExpectedContent[1]))
            );
        }
    }

    /**
     * @return string[][]
     */
    public function propertiesProvider()
    {
        return [
            [
                'form-generator-model-property.yaml',
                'UserCreate',
                [
                    [
                        'Symfony\Component\Form\Extension\Core\Type\TextType',
                        <<<EOF
->add('firstName', TextType::class, [
    'constraints' => [
        new NotBlank(),
    ]
])
EOF
                    ],
                    [
                        'Symfony\Component\Form\Extension\Core\Type\EmailType',
                        <<<EOF
->add('email', EmailType::class, [
    'constraints' => [
        new NotBlank(),
    ]
])
EOF
                    ],
                    [
                        'Symfony\Component\Form\Extension\Core\Type\NumberType',
                        <<<EOF
->add('age', NumberType::class)
EOF
                    ],
                    [
                        'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                        <<<EOF
->add('type', ChoiceType::class, [
    'choices' => [
        'user', 'manager', 'admin',
    ],
])
EOF
                    ],
                    [
                        [
                            'Symfony\Component\Form\Extension\Core\Type\EntityType',
                            'AppBundle\Entity\Shop',
                        ],
                        <<<EOF
->add('shopId', EntityType::class, [
    'class' => Shop::class,
])
EOF
                    ],
                    [
                        'Symfony\Component\Form\Extension\Core\Type\DateType',
                        <<<EOF
->add('birthDate', DateType::class, [
    'format' => DateType::HTML5_FORMAT,
    'widget' => 'single_text',
])
EOF
                    ],
                    [
                        'Symfony\Component\Form\Extension\Core\Type\TextareaType',
                        <<<EOF
->add('biography', TextareaType::class)
EOF
                    ],
                    [
                        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
                        <<<EOF
->add('someFieldForDateTime', DateTimeType::class, [
    'format' => DateTimeType::HTML5_FORMAT,
    'widget' => 'single_text',
])
EOF
                    ],
                    [
                        'Symfony\Component\Form\Extension\Core\Type\TimeType',
                        <<<EOF
->add('someFieldForTime', TimeType::class, [
    'input' => 'string',
    'widget' => 'single_text',
])
EOF
                    ],
                ],
            ],
            [
                'form-generator-model-property.yaml',
                'TestAddressFormWithCollectionSubFormInput',
                [
                    [
                        [
                            'Symfony\Component\Form\Extension\Core\Type\CollectionType',
                            'AppBundle\Form\User\UserType',
                        ],
                        <<<EOF
->add('users', CollectionType::class, [
    'entry_type' => UserType::class,
    'allow_add' => true,
    'allow_delete' => true,
    'by_reference' => false,
])
EOF
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $filename
     * @param string $elementName
     * @param string $exception
     * @param string $message
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     *
     * @dataProvider propertiesExceptionProvider
     */
    public function testPropertiesException(string $filename, string $elementName, string $exception, string $message)
    {
        static::expectException($exception);
        static::expectExceptionMessage($message);

        $this->generateModel($this->getFormCollection($filename)->findElement($elementName));
    }

    /**
     * @return string[][]
     */
    public function propertiesExceptionProvider()
    {
        return [
            [
                'form-generator-model-property.yaml',
                'TestErrorExceptionInput',
                \LogicException::class,
                sprintf("Form property type '%s' cannot get supported render", 'wrong'),
            ],
        ];
    }
}
