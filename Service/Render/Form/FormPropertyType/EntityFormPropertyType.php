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
        return
            $formProperty->isEntity()
            && empty($formProperty->getType())
        ;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        $entity = $formProperty->getReferencedObject();

        $formPropertyConstraintDto = $this->getNeededConstraints($formProperty);

        $optionsContent = <<<EOF
    'class' => {$entity->getName()}::class,
EOF;

        return (new FormPropertyRenderOutput())
            ->addUseSections([
                'Symfony\Component\Form\Extension\Core\Type\EntityType',
                sprintf('%s\%s', $this->bundleName, $entity->getNameSpace()),
            ])
            ->addUseSections($formPropertyConstraintDto->getUses())
            ->setContent($this->wrapProperty(
                $formProperty->getNameCamelCase(),
                    'EntityType',
                    $formPropertyConstraintDto->getContents(),
                    $optionsContent
                )
            )
        ;
    }
}
