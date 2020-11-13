<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
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
     * @Given I am on the login page
     * 
     * @throws Exception
     */
    public function iAmOnTheLoginPage(): void
    {
        $this->visitPath("/login");
    }

    /**
     * @When I request the list of users from :uri
     */
    public function iRequestTheListOfUsersFrom(string $uri): void
    {
        $this->visitPath($uri);
    }

    /**
     * @Then I see the page with the title :h1
     * 
     * @throws Exception
     */
    public function iSeeThePageWithTheTitle(string $h1): void
    {
        $title = $this->getSession()->getPage()->find('css', 'h1');
        if ($h1 !== $title->getText()) {
            throw new Exception("La page obtenue ne contient pas le titre 'Liste des utilisateurs'");
        }
    }
}
