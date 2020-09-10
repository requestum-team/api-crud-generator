<?php

namespace Requestum\ApiGeneratorBundle\Service\Generator;

/**
 * Interface ClassGeneratorInterface
 *
 * @package Requestum\ApiGeneratorBundle\Service\Generator
 */
interface ClassGeneratorModelInterface
{
    public function getName(): string;

    public function getFilePath(): string;

    public function getNameSpace(): string;

    public function getUseSection(): array;

    public function getExtendsClass(): ?string;

    public function getImplementedInterfaces(): array;

    public function getAnnotations(): array;

    public function getTraits(): array;

    public function getConstants(): array;

    public function getProperties(): array;

    public function getMethods(): array;

    public function isReady(): bool;
}
