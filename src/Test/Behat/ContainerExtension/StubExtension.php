<?php

namespace Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class StubExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
    }

}
