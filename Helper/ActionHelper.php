<?php

namespace Requestum\ApiGeneratorBundle\Helper;

use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\BaseModel;

/**
 * Class ActionHelper
 *
 * @package Requestum\ApiGeneratorBundle\Helper
 */
class ActionHelper
{
    /** @var string */
    const ACTION_FETCH = 'fetch';
    /** @var string */
    const ACTION_LIST = 'list';
    /** @var string */
    const ACTION_CREATE = 'create';
    /** @var string */
    const ACTION_UPDATE = 'update';
    /** @var string */
    const ACTION_DELETE = 'delete';

    /**
     * @param string $method
     * @param string|null $operationId
     *
     * @return string|null
     */
    public static function getActionClassByMethod(string $method, ?string $operationId = null): ?string
    {
        switch ($method) {
            case BaseModel::ALLOWED_METHOD_POST:
                return Action::DEFAULT_ACTION_CREATE;
            case BaseModel::ALLOWED_METHOD_PATCH:
            case BaseModel::ALLOWED_METHOD_PUT:
                return Action::DEFAULT_ACTION_UPDATE;
            case BaseModel::ALLOWED_METHOD_DELETE:
                return Action::DEFAULT_ACTION_DELETE;
            case BaseModel::ALLOWED_METHOD_GET:
                if (!empty($operationId)
                    && preg_match(
                        sprintf("/(?:[\W]?(%s|%s)[\W]?)/ui", self::ACTION_FETCH, self::ACTION_LIST),
                        $operationId,
                        $m
                    )
                    && !empty($m[1])
                ) {
                    switch ($m[1]) {
                        case self::ACTION_FETCH:
                            return Action::DEFAULT_ACTION_FETCH;
                        case self::ACTION_LIST:
                            return Action::DEFAULT_ACTION_LIST;
                    }
                }
                break;
        }

        return null;
    }
}
