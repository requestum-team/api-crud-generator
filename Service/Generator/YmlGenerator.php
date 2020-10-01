<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\Routing;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YmlGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
class YmlGenerator
{
    /** @var string */
    protected string $bundleName;

    /**
     * @param string $bundleName
     */
    public function __construct(string $bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @param Action[] $actionNode
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generateActionNode(array $actionNode): string
    {
        $data = ['services' => null];

        foreach ($actionNode as $action) {
            $this->prepareAction($action);

            $actionService = [
                'class'     => $action->getClassName(),
                'arguments' => $action->getArguments(),
                //todo 'calls'     => [],
                'tags'      => ['controller.service_arguments',],
            ];

            $data['services'][$action->getServiceName()] = $actionService;
        }

        return Yaml::dump($data, 4);
    }

    /**
     * @param Routing[] $routingNode
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generateRoutingNode(array $routingNode): string
    {
        $data = [];

        foreach ($routingNode as $routing) {
            $data[$routing->getServiceName()] = [
                'path'     => $routing->getPath(),
                'methods'  => $routing->getMethod(),
                'defaults' => [
                    '_controller' => $routing->getControllerName(),
                ],
            ];
        }

        return Yaml::dump($data, 4);
    }

    /**
     * @param Action $action
     */
    protected function prepareAction(Action $action)
    {
        $arguments = $action->getArguments();

        if (!empty($form = $action->getForm())) {
            array_unshift(
                $arguments,
                sprintf(
                    '%s\%s\%s%s',
                    $this->bundleName,
                    $form->getNameSpace(),
                    $form->getName(),
                    FormGeneratorModelBuilder::NAME_POSTFIX
                )
            );
        }

        if (!empty($entity = $action->getEntity())) {
            array_unshift($arguments, sprintf('%s\%s', $this->bundleName, $entity->getNameSpace()));
        }

        $action->setArguments($arguments);
    }
}
