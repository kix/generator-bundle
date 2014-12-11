<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 14:44
 */

namespace Kix\GeneratorBundle\Command;

use Kix\GeneratorBundle\Generator\ControllerGenerator;
use Kix\GeneratorBundle\Generator\ControllerSpec;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class GenerateControllerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('controller', '', InputOption::VALUE_REQUIRED, 'The name of the controller to create'),
                new InputOption('actions', '', InputOption::VALUE_REQUIRED, 'The actions in the controller'),
            ))
            ->setDescription('Generates a controller')
            ->setHelp(<<<EOT
Hello world
EOT
            )
            ->setName('kix:generate:controller')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $input->getOption('controller')) {
            throw new \RuntimeException('The controller option must be provided.');
        }

        $generator = $this->getContainer()->get('controller_generator');

        list($bundle, $controller) = $this->parseShortcutNotation($input->getOption('controller'));

        if (is_string($bundle)) {
            try {
                $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
                return;
            }
        }

        $generator->generate(new ControllerSpec(
            $bundle,
            $controller,
            explode(' ', $input->getOption('actions')),
            new ControllerSpec\Options(true)
        ));
    }

    public function parseShortcutNotation($shortcut)
    {
        $entity = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($entity, ':')) {
            throw new \InvalidArgumentException(sprintf('The controller name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Post)', $entity));
        }

        return array(substr($entity, 0, $pos), substr($entity, $pos + 1));
    }

}