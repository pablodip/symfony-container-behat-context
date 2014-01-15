<?php

namespace Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

class WithServiceExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $container->setDefinition('my_service', new Definition('stdClass'));
    }
}
