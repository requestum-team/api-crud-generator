<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class EntityGenerationStrategy
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
final class EntityGenerationStrategyEnum
{
    const GENERATION_STRATEGY_AUTO = 'auto';
    const GENERATION_STRATEGY_SEQUENCE = 'sequence';
    const GENERATION_STRATEGY_IDENTITY = 'identity';
    const GENERATION_STRATEGY_UUID = 'uuid';
    const GENERATION_STRATEGY_TABLE = 'table';
    const GENERATION_STRATEGY_NONE = 'none';
    const GENERATION_STRATEGY_CUSTOM = 'custom';
}
