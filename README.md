# ToDoList

[![pipeline status](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/badges/develop/pipeline.svg)](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/-/commits/develop) [![coverage report](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/badges/develop/coverage.svg)](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/-/commits/develop) [![Codacy Badge](https://app.codacy.com/project/badge/Grade/ae0cd12410f34ebcb7b62e502eb615ef)](https://www.codacy.com/gh/CarolineDirat/P8ImproveToDo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=CarolineDirat/P8ImproveToDo&amp;utm_campaign=Badge_Grade)[![Maintainability](https://api.codeclimate.com/v1/badges/489d8e2b6b95f91bead6/maintainability)](https://codeclimate.com/github/CarolineDirat/P8ImproveToDo/maintainability)

## Student Project : Improve an existing application

Project n°8 of "PHP/Symfony Application Developer" course on OpenClassrooms

The initial project to improve : <https://github.com/saro0h/projet8-TodoList>

### The improvements

- Correct anomalies.
- Add new features.
- Implementation of automated tests (with PHPUnit and Behat)
- Continuous integration on GitHub of Codacy, phpstan and checking security vulnerabilities
- Continuous integration on GitLab of PHPUnit tests on GitLab
- Technical documentation - [Implementation of authentication](https://github.com/CarolineDirat/P8ImproveToDo/blob/master/doc/Technical_Documentation_Authentication.pdf)
- Technical documentation of how contribute to the project - [CONTRIBUTING.md](https://github.com/CarolineDirat/P8ImproveToDo/blob/master/CONTRIBUTING.md)
- Code quality audit - [Audit_Quality.pdf](https://github.com/CarolineDirat/P8ImproveToDo/blob/master/Documentation/Audit_Quality.pdf)
- Code performance audit - [Audit_Performance.pdf](https://github.com/CarolineDirat/P8ImproveToDo/blob/master/Documentation/Audit_Performance.pdf)

## Instructions to install the P8ImproveToDo project

### Requirements

P8ImproveToDo installation needs :

- **Composer** : getcomposer.org/

- **Git** : git-scm.com/

- **PHP 7.4.*** : wwww.php.net

- **MySQL database** that you can eventually manage with a database tool (as *phpmyadmin* or *DBeaver*...).

- Symfony use the **URL rewriting**, then on Apache server, you must enable the *rewrite_module* module in the http.conf file.
  
- **Java** : <https://www.java.com/fr/> [to run selenium.jar, needed to functional tests of Behat that need Javascript.]

- Installation of [**Symfony CLI**](https://symfony.com/download) : That's create a binary named **symfony**  which provides all tools you need to develop and execute locally your Symfony application.

The **symfony** binary provides also a tool to check if your computer meets all requirements. Open your console terminal and run this command :

```
symfony check:requirements
```

````
# By example, you might specify in the php.ini file:
memory_limit = 128M
realpath_cache_size = 5M
# activation of [opcache] PHP extension:
opcache.enable=On
opcache.enable_cli=On
````

### Installation on a local server

---
The following instructions lead you to install the project on a HTTP server Apache(by example with Wampserser on Windows). See the [Symfony documentation](https://symfony.com/doc/current/setup.html#running-symfony-applications).

---

1. **Clone the project** from GitHub, at the root of your local server, with the command :

```
git clone https://github.com/CarolineDirat/P8ImproveToDo.git directoryName
```

*directoryName* is the name you give to the cloned repository. If you don't specify directoryName, the project will be cloned in the P8ImproveToDo directory. To obtain by example : C:/wamp/www/P8ImproveToDo in Wampserver

Then go to the root of the project, in the created repository :

    cd directoryName
---

2. **At the root of the project** (by example C:/wamp/www/P8ImproveToDo>), use Composer to load  **vendor/** et **var/** directories, with the command :

````
composer install
````

---

3. Create the **virtualhost** on Wampserver. Warning, the virtualhost must point on the **public/** directory.

By example : C:/wamp/www/P8ImproveToDo/public

---

4. **Install** at the root of the project **selenium.jar** (<https://www.selenium.dev/downloads/>) and **geckodriver.exe** executables (<https://github.com/mozilla/geckodriver/releases/tag/v0.28.0>) to execute the functional tests of Behat which need JavaScript.

**NB :** *geckodriver.exe* is corresponding to [Mozilla Firefox](https://www.mozilla.org/fr/firefox/new/) browser that is must be installed on your computer.
If you use another browser, see the [selenium documentation](https://www.selenium.dev/downloads/) to install the corresponding driver.

---

5. [Overriding Environment Values via .env.local](https://symfony.com/doc/current/configuration.html#overriding-environment-values-via-env-local) :

At the root of the project, creates the **.env.dev.local** file to specify yours environment values (see the **.env** file) :
**DATABASE_URL** (according to the access data to your MYSQL database) and **APP_DOMAIN** (according to your virtualhost).

By example :

```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name_dev?serverVersion=5.7"
APP_DOMAIN=http://p8improvetodo
```

---

6. Make sure you are in **development mode** with **APP_ENV=dev** in the **.env** file.

---

7. Now that your connection parameters are defined, Doctrine can **create** for you **the database** db_name_dev, with the command:

````
php bin/console doctrine:database:create
````

That's create the database named "db_name_dev" that you can specify in the DATABASE_URL environment value, in the .env.dev.local file.

Then you can **create tables** in the database with the command :

````
php bin/console doctrine:migrations:migrate
````

Answers "yes" to the question : "WARNING! You are about to execute a database migration that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]:"

---

8. Load the **initial data** for the development mode.

The initial data are loaded from the [src/DataFixtures/AppFixtures.php](https://github.com/CarolineDirat/P8ImproveToDo/blob/master/src/DataFixtures/AppFixtures.php) file thanks to the command :

````
php bin/console doctrine:fixtures:load
````

Answers "yes" to the question : "Careful, database "db_name_dev" will be purged. Do you want to continue? (yes/no) [no]:"

There are 3 users, including 1 administrator with the "ROLE_ADMIN" role (which allows access to users management) :

 username | password
----------|---------
  user1   | password
  user2   | password
  admin   | password

  Then there are 51 tasks, including 25 are marked as done. Some tasks have for author user1, others for user2, others for admin, and others without author.

---

9. You can now go to the homepage, on the "/" URI. You can then log as user1, or user2 or admin to access the tasks management. Only the admin user has access to the users management (via the "Admin" button in the navigation bar).

---

### The code coverage report

Once the app is installed, you can access the code coverage report on the "/test-coverage/index.html" URI.

### To contribute to the project

To contribute to the project, please follow the instructions detailed in the [CONTRIBUTING.md](https://github.com/CarolineDirat/P8ImproveToDo/blob/master/CONTRIBUTING.md) file, at the root of the project.
