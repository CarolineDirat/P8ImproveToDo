<?php

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Kernel;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\BrowserKit\Client;
use WebDriver\Exception\NoAlertOpenError;

require_once __DIR__.'/../../config/bootstrap.php';
require_once __DIR__.'/../../src/Kernel.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    /**
     * container.
     *
     * @var mixed
     */
    private static $container;

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
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $scope): void
    {
        // prepare system for test suite
        // before it runs
        self::bootstrapSymfony();

        /** @var Registry $doctrine */
        $doctrine = self::$container->get('doctrine');

        /** @var ObjectManager[] $managers */
        $managers = $doctrine->getManagers();

        foreach ($managers as $manager) {
            if ($manager instanceof EntityManagerInterface) {
                $schemaTool = new SchemaTool($manager);
                $schemaTool->dropDatabase();
                $schemaTool->createSchema($manager->getMetadataFactory()->getAllMetadata());
            }
        }

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine.orm.default_entity_manager');

        $loader = new Loader();
        $loader->addFixture(new AppFixtures());
        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * @Given I am authenticated user as :username with :passsword
     */
    public function iAmAuthenticatedUserAsWith(string $username, string $password): void
    {
        $this->visit('/login');
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Se connecter');
    }

    /**
     * @Given I am on the home page
     */
    public function iAmOnTheHomePage(): void
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
            throw new PendingException('La page obtenue ne contient pas le titre "'.$h1.'" mais : "'.$title->getText().'".');
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
     *
     * @param TableNode<array<string>> $table
     */
    public function iFillInTheFollowing(TableNode $table): void
    {
        $this->fillFields($table);
    }

    /**
     * @When I wait for :ms
     */
    public function iWaitFor(string $ms): void
    {
        $this->getSession()->wait((int) $ms);
    }

    /**
     * @When I follow redirect
     */
    public function iFollowRedirect(): void
    {
        $client = $this->getClient();
        $client->followRedirects();
        $client->followMetaRefresh();
    }

    /**
     * @Then Delete user :username
     */
    public function deleteUser(string $username): void
    {
        self::bootstrapSymfony();
        $entityManager = self::$container->get('doctrine')->getManager();
        $repository = self::$container->get('doctrine')->getRepository(User::class);
        /** @var User $user */
        $user = $repository->findOneBy(['username' => $username]);
        $entityManager->remove($user);
        $entityManager->flush();
    }

    public static function bootstrapSymfony(): void
    {
        $kernel = new Kernel('dev', true);
        $kernel->boot();
        self::$container = $kernel->getContainer();
    }

    /**
     * @Then I see the button :text
     *
     * @param mixed $text
     */
    public function iSeeTheButton($text): void
    {
        $this->assertElementContainsText('button', $text);
    }

    /**
     * Returns current active mink session.
     *
     * @throws UnsupportedDriverActionException
     *
     * @return Client
     */
    protected function getClient()
    {
        $driver = $this->getSession()->getDriver();

        if (!$driver instanceof BrowserKitDriver) {
            $message = 'This step is only supported by the browserkit drivers';

            throw new UnsupportedDriverActionException($message, $driver);
        }

        return $driver->getClient();
    }
}
