<?php

namespace Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class WithConfigRequiredExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($this->createTreeBuilder());
        $config = $this->processConfiguration($configuration, $configs);
    }

    private function createTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->getAlias());

        $rootNode
            ->children()
            ->scalarNode('foo')->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
