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
            && $formProperty->getFormat() === PropertyFormatEnum::FORMAT_DATETIME
        ;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        $formPropertyConstraintDto = $this->getNeededConstraints($formProperty);

        $optionsContent = <<<EOF
    'format' => DateTimeType::HTML5_FORMAT,
        'widget' => 'single_text',
EOF;

        return (new FormPropertyRenderOutput())
            ->addUseSections([
                'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
            ])
            ->addUseSections($formPropertyConstraintDto->getUses())
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'DateTimeType',
                    $formPropertyConstraintDto->getContents(),
                    $optionsContent
                )
            )
        ;
    }
}
