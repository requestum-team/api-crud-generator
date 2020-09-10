<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyConstraint;

use Requestum\ApiGeneratorBundle\Model\FormProperty;

/**
 * Interface FormPropertyConstraintInterface
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyConstraint
 */
interface FormPropertyConstraintInterface
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isNeeded(FormProperty $formProperty): bool;

    /**
     * @param FormProperty $formProperty
     *
     * @return string
     */
    public static function getConstraintContent(FormProperty $formProperty): string;
}
