<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\MinkExtension\Context\MinkContext;

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
    public function iSeeThePageWithTheTitle($h1)
    {
        $title = $this->getSession()->getPage()->find('css', 'h1');
        if ($h1 !== $title->getText()) {
            throw new Exception("La page obtenue ne contient pas le titre '".$h1."'");
        }
    }
}
