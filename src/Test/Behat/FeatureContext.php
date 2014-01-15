<?php

namespace Pablodip\Behat\SymfonyContainerContext\Test\Behat;

use Behat\Behat\Context\BehatContext;
use Pablodip\Behat\SymfonyContainerContext\SymfonyContainerBehatContext;

class FeatureContext extends BehatContext
{
    private $symfonyContainerContext;

    public function __construct(array $parameters)
    {
        $this->symfonyContainerContext = new SymfonyContainerBehatContext();

        $this->useContext('symfony_container', $this->symfonyContainerContext);
    }
}
