<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyConstraint;

use Requestum\ApiGeneratorBundle\Model\FormProperty;

/**
 * Class LengthFormPropertyConstraint
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyConstraint
 */
class LengthFormPropertyConstraint implements FormPropertyConstraintInterface
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isNeeded(FormProperty $formProperty): bool
    {
        $length = $formProperty->getLength();

        return
            !empty($length->getMin())
            || !empty($length->getMax())
        ;
    }

    /**
     * @return string[]
     */
    public static function getConstraintUses(): array
    {
        return [
            'Symfony\Component\Validator\Constraints\Length',
        ];
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return string
     */
    public static function getConstraintContent(FormProperty $formProperty): string
    {
        $length = $formProperty->getLength();
        $options = [];

        if (!empty($length->getMin())) {
            $options[] = "'min' => {$length->getMin()}";
        }

        if (!empty($length->getMax())) {
            $options[] = "'max' => {$length->getMax()}";
        }

        return sprintf(
            'new Length([%s])',
            implode(', ', $options)
        );
    }
}
