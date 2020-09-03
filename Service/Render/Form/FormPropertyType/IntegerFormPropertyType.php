<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class IntegerFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class IntegerFormPropertyType extends FormPropertyTypeAbstract
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isSupport(FormProperty $formProperty): bool
    {
        return $formProperty->getType() === PropertyTypeEnum::TYPE_INTEGER;
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
                'Symfony\Component\Form\Extension\Core\Type\NumberType',
            ])
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'NumberType',
                    $this->getNeededConstraints($formProperty)
                )
            )
        ;
    }
}
