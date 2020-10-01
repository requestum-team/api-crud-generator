<?php

namespace Requestum\ApiGeneratorBundle\Service;

use Requestum\ApiGeneratorBundle\Exception\AccessLevelException;
use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Service\Builder\ActionBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\RoutingBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\BundleGenerator;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\EntityGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\EntityRepositoryGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\FormGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Generator\PhpGenerator;
use Requestum\ApiGeneratorBundle\Service\Generator\YmlGenerator;
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
    protected PhpGenerator $phpGenerator;

    /** @var YmlGenerator */
    protected YmlGenerator $ymlGenerator;

    /** @var Filesystem */
    protected Filesystem $fs;

    /**
     * @var Config
     */
    protected $config;

    /** @var array */
    protected array $openApiSchema;

    /** @var string */
    protected string $outputDirectory;

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

    /**
     * @param array $openApiSchema
     * @param string $outputDirectory
     * @param Config $config
     */
    public function __construct(array $openApiSchema, string $outputDirectory, Config $config)
    {
        $this->config = $config;

        $this->phpGenerator = new PhpGenerator();
        $this->ymlGenerator = new YmlGenerator($this->config->bundleName);
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

        $this->actionCollection = (new ActionBuilder())
            ->build(
                $this->openApiSchema,
                $this->entityCollection,
                $this->formCollection
            )
        ;

        $this->routingCollection = (new RoutingBuilder())
            ->build(
                $this->openApiSchema,
                $this->actionCollection
            )
        ;

        $this->generateEntity();
        $this->generateForm();
        $this->generateAction();
        $this->generateRouting();
    }

    /**
     * Build base file system for generated bundle
     */
    protected function buildBaseFileSystem()
    {
        if (!$this->fs->exists($this->outputDirectory)) {
            $this->fs->mkdir($this->outputDirectory);
        }

        // src/AppBundle
        $bundleDir = implode(DIRECTORY_SEPARATOR, [$this->outputDirectory, $this->config->bundleName]);

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

            $this->generateEntityRepository($dump);
        }
    }

    /**
     * @param Entity $entity
     */
    protected function generateEntityRepository(Entity $entity)
    {
        $generatorModelBuilder = new EntityRepositoryGeneratorModelBuilder($this->config->bundleName);
        $generatorModel = $generatorModelBuilder->buildModel($entity);
        $content = $this->phpGenerator->generate($generatorModel);

        $filePath = sprintf(
            '%s/%s',
            $this->repositoryDir,
            $generatorModel->getFilePath()
        );

        $this->fs->dumpFile($filePath, $content);
    }

    protected function generateForm()
    {
        if (!$this->config->isGenerateForm || $this->formCollection->isEmpty()) {
            return;
        }

        foreach ($this->formCollection->dump() as $key => $dump) {
            /** @var Form $dump */
            if (!$dump->isGenerate()) {
                continue;
            }

            $generatorModel = (new FormGeneratorModelBuilder($this->config->bundleName))->buildModel($dump);
            $content = $this->phpGenerator->generate($generatorModel);

            $filePath = sprintf(
                '%s/%s',
                $this->formDir,
                $generatorModel->getFilePath()
            );

            $this->fs->dumpFile($filePath, $content);
        }
    }

    /**
     * @throws \Exception
     */
    protected function generateAction()
    {
        if (!$this->config->isGenerateAction || $this->actionCollection->isEmpty()) {
            return;
        }

        foreach ($this->actionCollection->getElements() as $key => $node) {
            $content = $this->ymlGenerator->generateActionNode($node);

            $filePath = sprintf(
                '%s/%s.%s',
                $this->actionsDir,
                $key,
                'yml'
            );

            $this->fs->dumpFile($filePath, $content);
        }
    }

    /**
     * @throws \Exception
     */
    protected function generateRouting()
    {
        if (!$this->config->isGenerateRoute || $this->routingCollection->isEmpty()) {
            return;
        }

        foreach ($this->routingCollection->getElements() as $key => $node) {
            $content = $this->ymlGenerator->generateRoutingNode($node);

            $filePath = sprintf(
                '%s/%s.%s',
                $this->routingDir,
                $key,
                'yml'
            );

            $this->fs->dumpFile($filePath, $content);
        }
    }
}
