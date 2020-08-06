<?php

namespace Requestum\ApiGeneratorBundle\Service;

use Requestum\ApiGeneratorBundle\Generators\BundleGenerator;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Filesystem;

use Requestum\ApiGeneratorBundle\Model\ActionCollection;
use Requestum\ApiGeneratorBundle\Model\EntityCollection;
use Requestum\ApiGeneratorBundle\Model\FormCollection;
use Requestum\ApiGeneratorBundle\Model\RoutingCollection;


class Generator
{
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
     * @var array
     */
    protected $entityCollection;

    /**
     * @var array
     */
    protected $formCollection;

    /**
     * @var array
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

        $this->fs = new Filesystem();

        $this->openApiSchema = $openApiSchema;
        $this->outputDirectory = $outputDirectory;

//        $this->actionCollection = new ActionCollection($config);
//        $this->entityCollection = new EntityCollection($config);
//        $this->formCollection = new FormCollection($config);
//        $this->routingCollection = new RoutingCollection($config);
    }

    public function generate()
    {
        $this->buildBaseFileSystem();
        $this->buildEntity();


//        $this->generateAction();
//        $this->generateEntity();
//        $this->generateForm();
//        $this->generateRouting();
    }

    protected function buildEntity()
    {
        $entityBuilder = new EntityBuilder($this->config);
        $this->entityCollection = $entityBuilder->build($this->openApiSchema);
//        var_dump($this->entityCollection); exit;
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

    protected function generateEntity()
    {
        if (!$this->config->isGenerateEntity || $this->entityCollection->isEmpty()) {
            return;
        }

//        foreach ($this->entityCollection->dump() as $key => $dump) {
//            // src/AppBundle/Resources/config/actions/action.yml
//            $bundleFile = implode(DIRECTORY_SEPARATOR, [$this->actionsDir, $key . '.yml']);
//
//            $this->fs->dumpFile(
//                $bundleFile,
//                Yaml::dump($dump, 4)
//            );
//        }
    }

    protected function generateForm()
    {
        if (!$this->config->isGenerateForm || $this->formCollection->isEmpty()) {
            return;
        }

//        foreach ($this->entityCollection->dump() as $key => $dump) {
//            // src/AppBundle/Resources/config/actions/action.yml
//            $bundleFile = implode(DIRECTORY_SEPARATOR, [$this->actionsDir, $key . '.yml']);
//
//            $this->fs->dumpFile(
//                $bundleFile,
//                Yaml::dump($dump, 4)
//            );
//        }
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
