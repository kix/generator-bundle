<?php

namespace Kix\GeneratorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KixGeneratorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');

        $container->setParameter('skeleton_dirs', array(
            __DIR__ . '/../Resources/skeleton',
        ));

        $processorDispatcher = $container->getDefinition('processor_dispatcher');

        foreach (array_keys($container->findTaggedServiceIds('generator.ast_processor')) as $astListenerId) {
            $processorDispatcher->addMethodCall(
                'addProcessor',
                array(
                    new Reference($astListenerId)
                )
            );
        }

        if ($container->getParameter('kernel.environment') == 'test') {
            $loader->load('services_test.yml');
        }
    }
}
