<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\FormProperty;

/**
 * Interface FormPropertyTypeAbstract
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
abstract class FormPropertyTypeAbstract implements FormPropertyTypeInterface
{
    /** @var string */
    protected string $bundleName;

    /**
     * FormPropertyTypeAbstract constructor.
     *
     * @param string $bundleName
     */
    public function __construct(string $bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @param string $name
     * @param string $typeClass
     * @param string|null $optionsContent
     *
     * @return string
     */
    protected static function getPropertyWrapper(
        string $name,
        string $typeClass,
        string $optionsContent = null
    ): string {
        $content = <<<EOF
\n    ->add('{$name}', {$typeClass}::class
EOF;

        if (!empty($optionsContent)) {
            $content .= <<<EOF
, [
    $optionsContent
    ]
EOF         ;
        }

        $content .= <<<EOF
)
EOF;

        return $content;
    }
}
