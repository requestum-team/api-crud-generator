<?php

namespace Requestum\ApiGeneratorBundle\Model;

class FormCollection extends BaseAbstractCollection
{
    public function dump(): array
    {
        return $this->getElements();
    }
}
