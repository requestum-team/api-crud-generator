<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class StringFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class StringFormPropertyType extends FormPropertyTypeAbstract
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isSupport(FormProperty $formProperty): bool
    {
        return
            $formProperty->getType() === PropertyTypeEnum::TYPE_STRING
            && empty($formProperty->getFormat())
            && empty($formProperty->getEnum())
            && !$formProperty->isEntity()
            && !$formProperty->isForm()
        ;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        return (new FormPropertyRenderOutput())
            ->setUseSections([
                'Symfony\Component\Form\Extension\Core\Type\TextType',
            ])
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'TextType',
                    $this->getNeededConstraints($formProperty)
                )
            )
        ;
    }
}
