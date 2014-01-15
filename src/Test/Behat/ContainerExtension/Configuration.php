<?php

namespace Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension;

use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $treeBuilder;

    public function __construct($treeBuilder)
    {
        $this->treeBuilder = $treeBuilder;
    }

    public function getConfigTreeBuilder()
    {
        return $this->treeBuilder;
    }
}
