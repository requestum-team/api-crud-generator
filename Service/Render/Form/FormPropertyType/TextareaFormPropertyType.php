<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Enum\PropertyFormatEnum;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class TextareaFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class TextareaFormPropertyType extends FormPropertyTypeAbstract
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
            && $formProperty->getFormat() === PropertyFormatEnum::TYPE_TEXT
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
                'Symfony\Component\Form\Extension\Core\Type\TextareaType',
            ])
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'TextareaType',
                    $this->getNeededConstraints($formProperty)
                )
            )
        ;
    }
}
