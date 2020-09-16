<?php

namespace Requestum\ApiGeneratorBundle\Service;

use Requestum\ApiGeneratorBundle\Exception\AccessLevelException;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Service\Generator\BundleGenerator;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\EntityGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\FormGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\PhpGenerator;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Filesystem;

use Requestum\ApiGeneratorBundle\Model\ActionCollection;
use Requestum\ApiGeneratorBundle\Model\EntityCollection;
use Requestum\ApiGeneratorBundle\Model\FormCollection;
use Requestum\ApiGeneratorBundle\Model\RoutingCollection;

/**
 * Class Generator
 *
 * @package Requestum\ApiGeneratorBundle\Service
 */
class Generator
{
    /** @var PhpGenerator */
    protected $phpGenerator;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $openApiSchema;

    /**
     * @var string
     */
    protected $outputDirectory;

    /**
     * @var EntityCollection
     */
    protected $entityCollection;

    /**
     * @var FormCollection
     */
    protected $formCollection;

    /**
     * @var RoutingCollection
     */
    protected $routingCollection;

    /**
     * @var ActionCollection
     */
    protected $actionCollection;

    /**
     * @var string
     */
    protected $actionsDir;

    /**
     * @var string
     */
    protected $entityDir;

    /**
     * @var string
     */
    protected $formDir;

    /**
     * @var string
     */
    protected $repositoryDir;

    /**
     * @var string
     */
    protected $routingDir;

    public function __construct(array $openApiSchema, string $outputDirectory, Config $config)
    {
        $this->config = $config;

        $this->phpGenerator = new PhpGenerator();
        $this->fs = new Filesystem();

        $this->openApiSchema = $openApiSchema;
        $this->outputDirectory = $outputDirectory;
    }

    /**
     * @throws AccessLevelException
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     * @throws \Exception
     */
    public function generate()
    {
        $this->buildBaseFileSystem();

        $inheritanceHandler = new InheritanceHandler();
        $schemasAndRequestBodiesCollection = $inheritanceHandler->process($this->openApiSchema);

        $entityBuilder = new EntityBuilder();
        $this->entityCollection = $entityBuilder->build($schemasAndRequestBodiesCollection);

        $formBuilder = new FormBuilder();
        $this->formCollection = $formBuilder->build($schemasAndRequestBodiesCollection, $this->entityCollection);


//        $this->generateAction();
        $this->generateEntity();
        $this->generateForm();
//        $this->generateRouting();
    }

    /**
     * Build base file system for geberated bundle
     */
    protected function buildBaseFileSystem()
    {
        // src
        $outputDir = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', $this->outputDirectory]);
        if (!$this->fs->exists($outputDir)) {
            $this->fs->mkdir($outputDir);
        }

        // src/AppBundle
        $bundleDir = implode(DIRECTORY_SEPARATOR, [$outputDir, $this->config->bundleName]);

        if (!$this->fs->exists($bundleDir)) {
            $this->fs->mkdir($bundleDir);
        }

        // src/AppBundle/AppBundle.php
        $bundleFile = implode(DIRECTORY_SEPARATOR, [$bundleDir, $this->config->bundleName . '.php']);

        $this->fs->dumpFile(
            $bundleFile,
            BundleGenerator::generate($this->config->bundleName)
        );

        if ($this->config->isGenerateAction || $this->config->isGenerateRoute) {
            // src/AppBundle/Resources
            $resourcesDir = implode(DIRECTORY_SEPARATOR, [$bundleDir, 'Resources']);

            if (!$this->fs->exists($resourcesDir)) {
                $this->fs->mkdir($resourcesDir);
            }

            // src/AppBundle/Resources/config
            $configDir = implode(DIRECTORY_SEPARATOR, [$resourcesDir, 'config']);
            if (!$this->fs->exists($configDir)) {
                $this->fs->mkdir($configDir);
            }

            if ($this->config->isGenerateAction) {
                // src/AppBundle/Resources/config/actions
                $this->actionsDir = implode(DIRECTORY_SEPARATOR, [$configDir, 'actions']);
                if (!$this->fs->exists($this->actionsDir)) {
                    $this->fs->mkdir($this->actionsDir);
                }
            }

            if ($this->config->isGenerateRoute) {
                // src/AppBundle/Resources/config/routing
                $this->routingDir = implode(DIRECTORY_SEPARATOR, [$configDir, 'routing']);
                if (!$this->fs->exists($this->routingDir)) {
                    $this->fs->mkdir($this->routingDir);
                }
            }
        }

        if ($this->config->isGenerateForm) {
            // src/AppBundle/Form
            $this->formDir = implode(DIRECTORY_SEPARATOR, [$bundleDir, 'Form']);
            if (!$this->fs->exists($this->formDir)) {
                $this->fs->mkdir($this->formDir);
            }
        }

        if ($this->config->isGenerateEntity) {
            // src/AppBundle/Entity
            $this->entityDir = implode(DIRECTORY_SEPARATOR, [$bundleDir, 'Entity']);
            if (!$this->fs->exists($this->entityDir)) {
                $this->fs->mkdir($this->entityDir);
            }

            // src/AppBundle/Repository
            $this->repositoryDir = implode(DIRECTORY_SEPARATOR, [$bundleDir, 'Repository']);
            if (!$this->fs->exists($this->repositoryDir)) {
                $this->fs->mkdir($this->repositoryDir);
            }
        }
    }

    protected function generateAction()
    {
        if (!$this->config->isGenerateAction || $this->actionCollection->isEmpty()) {
            return;
        }

        foreach ($this->actionCollection->dump() as $key => $dump) {
            // src/AppBundle/Resources/config/actions/action.yml
            $bundleFile = implode(DIRECTORY_SEPARATOR, [$this->actionsDir, $key . '.yml']);

            $this->fs->dumpFile(
                $bundleFile,
                Yaml::dump($dump, 4)
            );
        }
    }

    /**
     * @throws AccessLevelException
     */
    protected function generateEntity()
    {
        if (!$this->config->isGenerateEntity || $this->entityCollection->isEmpty()) {
            return;
        }

        $generatorModelBuilder = new EntityGeneratorModelBuilder($this->config->bundleName);

        foreach ($this->entityCollection->dump() as $key => $dump) {
            /** @var Entity $dump */
            if (!$dump->isGenerate()) {
                continue;
            }

            $generatorModel = $generatorModelBuilder->buildModel($dump);
            $content = $this->phpGenerator->generate($generatorModel);

            $filePath = sprintf(
                '%s/%s',
                $this->entityDir,
                $generatorModel->getFilePath()
            );

            $this->fs->dumpFile($filePath, $content);
        }
    }

    protected function generateForm()
    {
        if (!$this->config->isGenerateForm || $this->formCollection->isEmpty()) {
            return;
        }

        $generatorModelBuilder = new FormGeneratorModelBuilder($this->config->bundleName);

        foreach ($this->formCollection->dump() as $key => $dump) {
            /** @var Form $dump */
            if (!$dump->isGenerate()) {
                continue;
            }

            $generatorModel = $generatorModelBuilder->buildModel($dump);
            $content = $this->phpGenerator->generate($generatorModel);

            $filePath = sprintf(
                '%s/%s',
                $this->formDir,
                $generatorModel->getFilePath()
            );

            $this->fs->dumpFile($filePath, $content);
        }
    }

    protected function generateRouting()
    {
        if (!$this->config->isGenerateRoute || $this->routingCollection->isEmpty()) {
            return;
        }

        foreach ($this->routingCollection->dump() as $key => $dump) {
            // src/AppBundle/Resources/config/routing/routing.yml
            $bundleFile = implode(DIRECTORY_SEPARATOR, [$this->routingDir, $key . '.yml']);

            $this->fs->dumpFile(
                $bundleFile,
                Yaml::dump($dump, 4)
            );
        }
    }
}
