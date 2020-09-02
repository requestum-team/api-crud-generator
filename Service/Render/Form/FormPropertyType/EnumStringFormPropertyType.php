<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class EnumStringFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class EnumStringFormPropertyType extends FormPropertyTypeAbstract
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
            && !empty($formProperty->getEnum())
        ;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        $enum = $formProperty->getEnum();
        $enum = "'" . implode("', '", $enum) . "',";

        $optionsContent = <<<EOF
    'choices' => [
            {$enum}
        ],
EOF     ;

        return (new FormPropertyRenderOutput())
            ->setUseSections([
                'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
            ])
            ->setContent(
                $this->getPropertyWrapper(
                    $formProperty->getNameCamelCase(),
                    'ChoiceType',
                    $optionsContent
                )
            )
        ;
    }
}
