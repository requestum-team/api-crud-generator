<?php

namespace Requestum\ApiGeneratorBundle\Exception;

use Throwable;

/**
 * Class ActionClassDefineException
 *
 * @package Requestum\ApiGeneratorBundle\Exception
 */
class ActionClassDefineException extends \LogicException
{
    /**
     * ActionClassException constructor.
     *
     * @param string $path
     * @param string $method
     * @param string|null $operationId
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $path,
        string $method,
        ?string $operationId = null,
        $code = 0,
        Throwable $previous = null
    )
    {
        $message = sprintf(
            'Cannot define action class for "%s" path, "%s" method%s.',
            $path,
            $method,
            !empty($operationId) ? ' and "' . $operationId . '" operation ID' : ''
        );

        parent::__construct($message, $code, $previous);
    }
}
