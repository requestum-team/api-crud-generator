<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Enum\PropertyFormatEnum;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class DateTimeFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class DateTimeFormPropertyType extends FormPropertyTypeAbstract
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
            && $formProperty->getFormat() === PropertyFormatEnum::TYPE_DATETIME
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
    'format' => DateTimeType::HTML5_FORMAT,
        'widget' => 'single_text',
EOF;

        return (new FormPropertyRenderOutput())
            ->setUseSections([
                'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
            ])
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'DateTimeType',
                    $this->getNeededConstraints($formProperty),
                    $optionsContent
                )
            )
        ;
    }
}
