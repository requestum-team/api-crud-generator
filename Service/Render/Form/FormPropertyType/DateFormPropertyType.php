<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Enum\PropertyFormatEnum;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class DateFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class DateFormPropertyType extends FormPropertyTypeAbstract
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
            && $formProperty->getFormat() === PropertyFormatEnum::FORMAT_DATE
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
    'format' => DateType::HTML5_FORMAT,
        'widget' => 'single_text',
EOF;

        return (new FormPropertyRenderOutput())
            ->addUseSections([
                'Symfony\Component\Form\Extension\Core\Type\DateType',
            ])
            ->addUseSections($formPropertyConstraintDto->getUses())
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'DateType',
                    $formPropertyConstraintDto->getContents(),
                    $optionsContent
                )
            )
        ;
    }
}
