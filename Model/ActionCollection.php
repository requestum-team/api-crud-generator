<?php

namespace Requestum\ApiGeneratorBundle\Model;


class ActionCollection extends BaseAbstractCollection
{
    /**
     * @param ModelInterface $element
     *
     * @return $this
     */
    public function addElement(ModelInterface $element)
    {
        $this->elements[$element->getName()] = $element;

        return $this;
    }

    public function dump(): array
    {
        $result = [];
        foreach ($this->getElements() as $key => $actions) {
            $services = [];
            /** @var Action $action */
            foreach ($actions as $action) {
                $service = [
                    'parent' => $action->getParent(),
                    'class' => $action->getClassName(),
                    'arguments' => $action->getArguments(),
                ];
//                if (!$this->config->autowire) {
//                    $service['tags'] = '["controller.service_arguments"]';
//                }

                $services[$action->getServiceName()] = $service;
            }

            $result[$key] = [
                'services' => $services
            ];
        }

        return $result;
    }
}
