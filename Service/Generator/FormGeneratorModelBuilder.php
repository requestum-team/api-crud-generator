<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

use Requestum\ApiGeneratorBundle\Exception\SubjectTypeException;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorMethodModel;
use Requestum\ApiGeneratorBundle\Model\Generator\GeneratorParameterModel;
use Requestum\ApiGeneratorBundle\Service\Render\FormPropertyRender;

/**
 * Class FormGeneratorModelBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
class FormGeneratorModelBuilder extends GeneratorModelBuilderAbstract
{
    /** @var string */
    const NAME_POSTFIX = 'Type';

    /**
     * @param Form|object $form
     *
     * @return ClassGeneratorModelInterface
     */
    public function buildModel(object $form): ClassGeneratorModelInterface
    {
        if (!$form instanceof Form) {
            throw new SubjectTypeException($form, Form::class);
        }

        $entity = $form->getEntity();
        $nameSpace = implode('\\', [$this->bundleName, 'Form', $entity->getName(),]);

        $this->baseUseSection($entity->getName());
        $this->prepareMethods($form);

        return (new ClassGeneratorModel())
            ->setName($form->getName() . self::NAME_POSTFIX)
            ->setNameSpace($nameSpace)
            ->setFilePath($this->prepareFilePath($form->getName()))
            ->setExtendsClass('AbstractApiType')
            ->setUseSection($this->useSection)
            ->setMethods($this->methods)
        ;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function prepareFilePath(string $name): string
    {
        return implode('.', [$name . self::NAME_POSTFIX, 'php']);
    }

    /**
     * @param string $entityName
     */
    private function baseUseSection(string $entityName)
    {
        $this->addUseSections([
            'AppBundle\Entity\\' . $entityName,
        ]);
    }

    /**
     * @param Form $form
     */
    private function prepareMethods(Form $form)
    {
        $this->methods[] = (new GeneratorMethodModel())
            ->setAccessLevel('public')
            ->setName('buildForm')
            ->addParameters((new GeneratorParameterModel)
                ->setType('Symfony\Component\Form\FormBuilderInterface')
                ->setName('builder')
            )
            ->addParameters((new GeneratorParameterModel)
                ->setType('array')
                ->setName('options')
            )
            ->setBody($this->renderFormBuilder($form->getProperties()))
        ;

        $this->methods[] = (new GeneratorMethodModel())
            ->setAccessLevel('public')
            ->setName('configureOptions')
            ->addParameters((new GeneratorParameterModel)
                ->setType('Symfony\Component\OptionsResolver\OptionsResolver')
                ->setName('resolver')
            )
            ->setBody(<<<EOF
\$resolver
    ->setDefaults([
        'data_class' => {$form->getEntity()->getName()}::class,
    ])
;
EOF         )
        ;
    }

    /**
     * @param FormProperty[] $properties
     *
     * @return string
     */
    private function renderFormBuilder(array $properties): string
    {
        $content = <<<EOF
\$builder
EOF     ;

        foreach ($properties as $property) {
            $formPropertyRender = new FormPropertyRender($this->bundleName, $property);
            $content .= $formPropertyRender->render();
            $this->addUseSections($formPropertyRender->getUseSections());
        }

        $content .= <<<EOF
\n;
EOF     ;

        return $content;
    }
}
