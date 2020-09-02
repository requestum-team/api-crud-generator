<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

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
            $formProperty->getType() === 'string'
            && $formProperty->getFormat() === 'date-time'
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
                $this->getPropertyWrapper(
                    $formProperty->getNameCamelCase(),
                    'DateTimeType',
                    $optionsContent
                )
            )
        ;
    }
}
