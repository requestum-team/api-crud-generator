<?php

namespace Requestum\ApiGeneratorBundle\Exception;

use Throwable;

/**
 * Class SubjectTypeException
 *
 * @package Requestum\ApiGeneratorBundle\Exception
 */
class SubjectTypeException extends \LogicException
{
    /**
     * SubjectTypeException constructor.
     *
     * @param object $subject
     * @param string $expectedClass
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(object $subject, string $expectedClass, $code = 0, Throwable $previous = null)
    {
        $message = sprintf(
            'Wrong subject type: %s. Expected class type: %s.',
            get_class($subject),
            $expectedClass
        );

        parent::__construct($message, $code, $previous);
    }
}
