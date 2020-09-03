<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Generator\FormGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class CollectionFormFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class CollectionFormFormPropertyType extends FormPropertyTypeAbstract
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isSupport(FormProperty $formProperty): bool
    {
        return
            $formProperty->isForm()
            && $formProperty->getType() === PropertyTypeEnum::TYPE_ARRAY
        ;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        /** @var Form $form */
        $form = $formProperty->getReferencedObject();
        $entity = $form->getEntity();
        $subFormClass = $entity->getName() . FormGeneratorModelBuilder::NAME_POSTFIX;
        $subFormNameSpace = sprintf('%s\%s\%s', $this->bundleName, $form->getNameSpace(), $subFormClass);

        $optionsContent = <<<EOF
    'entry_type' => {$subFormClass}::class,
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
EOF;

        return (new FormPropertyRenderOutput())
            ->setUseSections([
                'Symfony\Component\Form\Extension\Core\Type\CollectionType',
                $subFormNameSpace,
            ])
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'CollectionType',
                    $this->getNeededConstraints($formProperty),
                    $optionsContent
                )
            )
        ;
    }
}
