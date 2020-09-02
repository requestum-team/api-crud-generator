<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class EntityFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class EntityFormPropertyType extends FormPropertyTypeAbstract
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isSupport(FormProperty $formProperty): bool
    {
        return $formProperty->isEntity() && empty($formProperty->getType());
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        $entityName = $formProperty
            ->getReferencedObject()
            ->getName()
        ;

        $optionsContent = <<<EOF
        'class' => {$entityName}::class,
EOF;

        return (new FormPropertyRenderOutput())
            ->setUseSections([
                'Symfony\Component\Form\Extension\Core\Type\EntityType',
                sprintf('%s\Entity\%s', $this->bundleName, $entityName),
            ])
            ->setContent($this->getPropertyWrapper(
                $formProperty->getNameCamelCase(),
                    'EntityType',
                    $optionsContent
                )
            )
        ;
    }
}
