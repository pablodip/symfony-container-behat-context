<?php

namespace Pablodip\Behat\SymfonyContainerContext;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Pablodip\Symfony\ContainerConfigurator\Domain\TempFileCreator\TempFileCreatorInterface;
use Pablodip\Symfony\ContainerConfigurator\Domain\YamlConfigContainerConfigurator;
use Pablodip\Symfony\ContainerConfigurator\Infrastructure\TempFileCreator\BasicTempFileCreator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use felpado as f;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SymfonyContainerBehatContext extends BehatContext
{
    private $tempFileCreator;

    /** @var ContainerBuilder */
    private $container;

    public function __construct(TempFileCreatorInterface $tempFileCreator = null)
    {
        $this->tempFileCreator = $tempFileCreator ?: new BasicTempFileCreator();
    }

    public function getContainer()
    {
        if (is_null($this->container)) {
            throw new \RuntimeException('There is no container.');
        }

        return $this->container;
    }

    /**
     * @Given /^I have a symfony container$/
     */
    public function iHaveASymfonyContainer()
    {
        $this->container = new ContainerBuilder();
    }

    /**
     * @When /^I add the container parameter "([^"]*)" with "([^"]*)"$/
     */
    public function iAddTheContainerParameterWith($name, $value)
    {
        $this->getContainer()->setParameter($name, $value);
    }

    /**
     * @Given /^I add the container parameters:$/
     */
    public function iAddTheContainerParameters(TableNode $table)
    {
        foreach ($table->getRowsHash() as $n => $v) {
            $this->iAddTheContainerParameterWith($n, $v);
        }
    }

    /**
     * @Given /^the container parameter "([^"]*)" should be "([^"]*)"$/
     */
    public function theContainerParameterShouldBe($name, $expected)
    {
        $actual = $this->getContainer()->getParameter($name);

        if (f\not(f\equal($expected, $actual))) {
            throw new \Exception(sprintf('The container parameter "%s" is "%s" and it should be "%s"', $name, $expected, $actual));
        }
    }

    /**
     * @When /^I register the container extension "([^"]*)"$/
     */
    public function iRegisterTheContainerExtension($class)
    {
        if (f\not(class_exists($class))) {
            throw new \Exception(sprintf('The class "%s does not exist.', $class));
        }

        $extension = new $class();
        $this->getContainer()->registerExtension($extension);
    }

    /**
     * @Given /^I load the container from extension "([^"]*)"$/
     */
    public function iLoadTheContainerFromExtension($alias)
    {
        $this->getContainer()->loadFromExtension($alias);
    }

    /**
     * @When /^I load the container yaml config:$/
     */
    public function iLoadTheContainerYamlConfig(PyStringNode $string)
    {
        $yamlConfig = $string->getRaw();

        $fileLocator = new FileLocator(array(sys_get_temp_dir()));
        $yamlLoader = new YamlFileLoader($this->getContainer(), $fileLocator);

        $yamlConfigContainerConfigurator = new YamlConfigContainerConfigurator($this->tempFileCreator, $yamlConfig);
        $yamlConfigContainerConfigurator($yamlLoader);
    }

    /**
     * @Then /^the container parameters should be:$/
     */
    public function theContainerParametersShouldBe(TableNode $table)
    {
        foreach ($table->getRowsHash() as $n => $e) {
            $this->theContainerParameterShouldBe($n, $e);
        }
    }

    /**
     * @Then /^the container should be compilable$/
     */
    public function theContainerShouldBeCompilable()
    {
        $this->getContainer()->compile();
    }

    /**
     * @Then /^the container should not be compilable$/
     */
    public function theContainerShouldNotBeCompilable()
    {
        try {
            $this->getContainer()->compile();
        } catch (\Exception $e) {
            return;
        }

        throw new \Exception('The containter should not be compilable, but it is.');
    }

    /**
     * @Given /^the container should has the service "([^"]*)"$/
     */
    public function theContainerShouldHasTheService($id)
    {
        if (f\not($this->getContainer()->has($id))) {
            throw new \Exception(sprintf('The container should have the service "%s but it does not.', $id));
        }
    }

    /**
     * @Given /^the container service "([^"]*)" should be gettable$/
     */
    public function theContainerServiceShouldBeGettable($id)
    {
        $this->getContainer()->get($id);
    }
}
