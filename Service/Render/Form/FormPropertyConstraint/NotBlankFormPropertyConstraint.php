<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyConstraint;

use Requestum\ApiGeneratorBundle\Model\FormProperty;

/**
 * Class NotBlankFormPropertyConstraint
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyConstraint
 */
class NotBlankFormPropertyConstraint implements FormPropertyConstraintInterface
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isNeeded(FormProperty $formProperty): bool
    {
        return $formProperty->isRequired();
    }

    /**
     * @return string[]
     */
    public static function getConstraintUses(): array
    {
        return [
            'Symfony\Component\Validator\Constraints\NotBlank',
        ];
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return string
     */
    public static function getConstraintContent(FormProperty $formProperty): string
    {
        return 'new NotBlank()';
    }
}
