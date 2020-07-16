<?php

namespace Requestum\ApiGeneratorBundle\Service;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Requestum\ApiGeneratorBundle\Helper\FileHelper;

/**
 * Class Config
 * @package Requestum\ApiGeneratorBundle\Service
 *
 * @property string $bundleName
 * @property array $generate
 * @property boolean $autowire
 * @property boolean $autoconfigure
 * @property boolean $public
 * @property boolean $isGenerateAction
 * @property boolean $isGenerateEntity
 * @property boolean $isGenerateForm
 * @property boolean $isGenerateRoute
 */
class Config
{
    const SECTION_ALL = 'all';
    const SECTION_ENTITY = 'entity';
    const SECTION_FROM = 'form';
    const SECTION_ROUTE = 'route';
    const SECTION_ACTION = 'action';

    const POSSIBLE_SECTIONS = [
        self::SECTION_ALL,
        self::SECTION_ENTITY,
        self::SECTION_FROM,
        self::SECTION_ROUTE,
        self::SECTION_ACTION,
    ];

    /**
     * @var array
     */
    protected $config;

    /**
     * Config constructor
     * .
     * @param string|null $configPath
     *
     * @throws \Exception
     */
    public function __construct(?string $configPath)
    {
        $config = [];
        if (!is_null($configPath)) {
            $config = FileHelper::load($configPath);
        }

        $this->config = $this->resolveConfig($config);
    }

    public function __get($name)
    {
        if (!isset($this->config[$name])) {
            throw new \Exception(sprintf('Config parameter %s not found', $name));
        }

        return $this->config[$name];
    }

    protected function resolveConfig($config)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefaults([
                'bundleName' => 'AppBundle',
                'generate' => ['all'],
                'autowire' => true,
                'autoconfigure' => true,
                'public' => true,
            ])
            ->setAllowedTypes('autowire', 'boolean')
            ->setAllowedTypes('autoconfigure', 'boolean')
            ->setAllowedTypes('public', 'boolean')
            ->setNormalizer('generate', function (Options $options, $value) {
                if (!is_array($value)) {
                    $value = array_map('trim', explode(',', str_replace('=','', $value)));
                }
                $value = array_unique($value);
                $sectionValid = array_diff($value, Config::POSSIBLE_SECTIONS);
                if (count($sectionValid) > 0) {
                    throw new \Exception('Incorrect values for option generate. Possible values: ' . implode(', ', Config::POSSIBLE_SECTIONS));
                }

                return $value;
            })
            ->setDefault('isGenerateEntity', function (Options $options) {
                if (in_array(Config::SECTION_ALL, $options['generate'])) {
                    return true;
                }

                return in_array(Config::SECTION_ENTITY, $options['generate']);
            })
            ->setDefault('isGenerateForm', function (Options $options) {
                if (in_array(Config::SECTION_ALL, $options['generate'])) {
                    return true;
                }

                return in_array(Config::SECTION_FROM, $options['generate']);
            })

            ->setDefault('isGenerateRoute', function (Options $options) {
                if (in_array(Config::SECTION_ALL, $options['generate'])) {
                    return true;
                }

                return in_array(Config::SECTION_ROUTE, $options['generate']);
            })

            ->setDefault('isGenerateAction', function (Options $options) {
                if (in_array(Config::SECTION_ALL, $options['generate'])) {
                    return true;
                }

                return in_array(Config::SECTION_ACTION, $options['generate']);
            })
        ;

        return $resolver->resolve($config['config']);
    }
}
