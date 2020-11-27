<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\MinkContext;
use WebDriver\Exception\NoAlertOpenError;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I am authenticated user as :username with :passsword
     */
    public function iAmAuthenticatedUserAsWith(string $username, string $password)
    {
        $this->visit('/login');
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Se connecter');
    }

    /**
     * @Given I am on the home page
     */
    public function iAmOnTheHomePage()
    {
        $this->visit('/');
    }

    /**
     * @Then I see the page with the title :h1
     */
    public function iSeeThePageWithTheTitle(string $h1): void
    {
        $title = $this->getSession()->getPage()->find('css', 'h1');
        if ($h1 !== $title->getText()) {
            throw new Exception('La page obtenue ne contient pas le titre "'.$h1.'"');
        }
    }

    /**
     * @Then I see the alert message :class
     */
    public function iSeeTheAlertMessage(string $class): void
    {
        $this->getSession()->getPage()->find('css', $class);
    }

    /**
     * @Then I confirm the popup
     */
    public function iConfirmThePopup(): void
    {
        $i = 0;
        while ($i < 5) {
            try {
                /**
                 * @var Selenium2Driver $driver
                 */
                $driver = $this->getSession()->getDriver();
                $driver->getWebDriverSession()->accept_alert();

                break;
            } catch (NoAlertOpenError $e) {
                usleep(1000);
                ++$i;
            }
        }
    }

    /**
     * @When I fill in the following
     */
    public function iFillInTheFollowing(TableNode $table): void
    {
        $this->fillFields($table);
    }
}
