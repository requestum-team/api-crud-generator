<?php

namespace Requestum\ApiGeneratorBundle\Command;

use App\Kernel;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Service\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Requestum\ApiGeneratorBundle\Service\Generator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class ApiGeneratorCommand
 *
 * @package Requestum\ApiGeneratorBundle\Command
 */
class ApiGeneratorCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var int */
    const SUCCESS = 0;

    protected function configure()
    {
        $this
            ->setName('requestum:api:generator')
            ->setDescription('API generator from OopenAPI for Requestum Api Bundle')
            ->setHelp('This command allows you to create a user...')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument(
                        'inputSpec', InputArgument::REQUIRED,  'Path to spec file'
                    ),
                    new InputArgument(
                        'outputDirectory', InputArgument::OPTIONAL,  'Path to output directory',
                        'out'
                    ),
                    new InputOption(
                        'configPath',
                        'c',
                        InputOption::VALUE_OPTIONAL,
                        'Path to config file (YAML or JSON format)',
                        null
                    ),
                ])
            );
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputSpec = $input->getArgument('inputSpec');
        $outputDirectory = $input->getArgument('outputDirectory');

        $this->wrapPath($inputSpec);
        $this->wrapPath($outputDirectory);

        $openApiSchema = FileHelper::load($inputSpec);

        $configPath = $input->getOption('configPath');
        $config = new Config($configPath);

        $generator = new Generator(
            $openApiSchema,
            $outputDirectory,
            $config
        );

        $generator->generate();

        $output->writeln("Command success\n");

        return self::SUCCESS;
    }

    /**
     * @param $path
     */
    private function wrapPath(&$path)
    {
        if (preg_match("/^" . preg_quote(DIRECTORY_SEPARATOR, '/') . "/", $path)) {
            return;
        }

        $path = preg_replace(
            "/^\." . preg_quote(DIRECTORY_SEPARATOR, '/') . "/", '', $path
        );

        /** @var Kernel $kernel */
        $kernel = $this->container->get('kernel');

        $path = sprintf(
            '%s/%s',
            $kernel->getProjectDir(),
            $path
        );
    }
}
