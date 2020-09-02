<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Enum\PropertyFormatEnum;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class TimeFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class TimeFormPropertyType extends FormPropertyTypeAbstract
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
            && $formProperty->getFormat() === PropertyFormatEnum::TYPE_TIME
        ;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        $optionsContent = <<<EOF
    'input' => 'string',
        'widget' => 'single_text',
EOF;

        return (new FormPropertyRenderOutput())
            ->setUseSections([
                'Symfony\Component\Form\Extension\Core\Type\TimeType',
            ])
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'TimeType',
                    $this->getNeededConstraints($formProperty),
                    $optionsContent
                )
            )
        ;
    }
}
