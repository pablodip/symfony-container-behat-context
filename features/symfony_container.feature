Feature: Symfony Container
  In order to write symfony container features
  As a symfony container developer
  I want to be able to test it with behat

  Scenario: Simple container
    Given I have a symfony container
    Then the container should be compilable

  Scenario: Parameters
    Given I have a symfony container
    When I add the container parameter "a" with "1"
    And I add the container parameters:
      | b | 2 |
      | c | 3 |
    Then the container should be compilable
    And the container parameter "a" should be "1"
    And the container parameters should be:
      | b | 2 |
      | c | 3 |

  Scenario: Extensions
    Given I have a symfony container
    When I register the container extension "Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension\StubExtension"
    Then the container should be compilable

  Scenario: Config
    Given I have a symfony container
    And I register the container extension "Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension\WithConfigRequiredExtension"
    And I load the container from extension "with_config_required"
    When I load the container yaml config:
      """
      with_config_required:
        foo: bar
      """
    Then the container should be compilable

  Scenario: Not compilable
    Given I have a symfony container
    And I register the container extension "Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension\WithConfigRequiredExtension"
    And I load the container from extension "with_config_required"
    When I load the container yaml config:
      """
      with_config_required: ~
      """
    Then the container should not be compilable

  Scenario: Services
    Given I have a symfony container
    And I register the container extension "Pablodip\Behat\SymfonyContainerContext\Test\Behat\ContainerExtension\WithServiceExtension"
    And I load the container from extension "with_service"
    Then the container should be compilable
    And the container should has the service "my_service"
    And the container service "my_service" should be gettable
