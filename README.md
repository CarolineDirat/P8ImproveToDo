ToDoList
========

[![pipeline status](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/badges/develop/pipeline.svg)](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/-/commits/develop) [![coverage report](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/badges/develop/coverage.svg)](https://gitlab.com/Squirrel-Jo/P8ImproveToDo/-/commits/develop) [![Codacy Badge](https://app.codacy.com/project/badge/Grade/ae0cd12410f34ebcb7b62e502eb615ef)](https://www.codacy.com/gh/CarolineDirat/P8ImproveToDo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=CarolineDirat/P8ImproveToDo&amp;utm_campaign=Badge_Grade)

## Intructions pour installer le projet P8ImproveToDo

### Projet n°8 : Améliorez un projet existant

Formation de "Développeu.r.se d'application PHP/Symfony" chez OpenClassrooms
Projet initial à améliorer : <https://github.com/saro0h/projet8-TodoList>

- Corrections d'anomalies
- Ajout de nouvelles fonctionnalités
- Implémentation de tests automatisés (avec PHPUnit et Behat) - [lien]()
- Documentation technique - implémentation de l'authentification - [lien]()
- Documentation technique de contribution - [lien]()
- Audit de qualité de code - [lien]()
- Audit de qualité de performance - [lien]()

### Prérequis

L'installation du projet P8ImproveToDo nécessite :

- **Composer** : getcomposer.org/

- **Git** : git-scm.com/

- **PHP 7.4.*** : wwww.php.net

- Une **base de données MySQL** que vous pouvez éventuellement gérer avec un outils de base de données (comme *phpmyadmin* ou *DBeaver*...).

- Symfony utilise l'**URL rewriting**, donc sur Apache, vous devez activer le module *rewrite_module* dans le fichier http.conf.

- Installer [**Symfony CLI**](https://symfony.com/download) : Cela crée un binaire appelé **symfony** qui fournit tous les outils dont vous avez besoin pour développer et exécuter votre application Symfony localement.

Le binaire **symfony**  fournit également un outil pour vérifier si votre ordinateur répond à toutes les exigences pour Symfony. Ouvrez votre terminal de console et exécutez cette commande :

```
symfony check:requirements
```

    # Par exemple, vous devrez préciser dans php.ini :
    memory_limit = 128M
    realpath_cache_size = 5M
    # activation of [opcache] PHP extension:
    opcache.enable=On
    opcache.enable_cli=On

### Installation sur un server local

---
Les instructions qui suivent vous guident pour installer le projet, sur un serveur HTTP Apache (par exemple avec Wampserver). Voir la [documentation de Symfony](https://symfony.com/doc/current/setup.html#running-symfony-applications).

---

1. **Cloner le projet** depuis GitHub, à la racine de votre serveur local, avec la commande :

```
git clone https://github.com/CarolineDirat/P7APIBileMo.git directoryName
```

*directoryName* est le nom que vous donnez au dossier cloné. Si vous ne précisez pas directoryName, le projet sera cloné dans le dossier P8ImproveToDo. Pour obtenir par exemple : C:/wamp/www/P7APIBileMo dans Wampserver

Puis placer vous à la racine du projet, dans le dossier créé :

    cd directoryName
---

2. **A la racine du projet** (par exemple C:/wamp/www/P8ImproveToDo>), utilisez Composer pour charger les dossiers **vendor/** et **var/**, avec la commande :

````
composer install
````

---

3. Créer le **virtualhost** sur Wampserver. Attention, le virtualhost doit pointer sur le dossier **public/**
Par exemple : C:/wamp/www/P7APIBileMo/public

---

4. **Installer à la racine du projet** les exécutables **selenium.jar** (<https://www.selenium.dev/downloads/>) et **geckodriver.exe** (<https://github.com/mozilla/geckodriver/releases/tag/v0.28.0>) pour exécuter les tests fonctionnel de Behat qui nécessitent JavaScript.

**NB :** *geckodriver.exe* correspond à l'utilisation du navigateur [Mozilla Firefox](https://www.mozilla.org/fr/firefox/new/) qui doit donc être installé sur votre ordinateur.
Si vous utilisez un autre navigateur, voir la  [documentation de selenium](https://www.selenium.dev/downloads/) pour installer son driver correspondant.

---

5. [Overriding Environment Values via .env.local](https://symfony.com/doc/current/configuration.html#overriding-environment-values-via-env-local) :

A la racine du projet, créez le fichier **.env.dev.local** pour définir vos variables d'environnement (voir le fichier **.env**) :
**DATABASE_URL** (d'après les données d'accès à votre base de données MySQL) et **APP_DOMAIN** (d'après votre virtualhost).

Par exemple :

```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name_dev?serverVersion=5.7"
APP_DOMAIN=http://p7apibilemo
```

---

6. Assurez-vous que vous êtes en **mode développement** avec **APP_ENV=dev** dans le fichier **.env**

---

7. Maintenant que vos paramètres de connexion sont définis, Doctrine peut **créer** pour vous **la base de données** db_name_dev, avec la commande :

````
php bin/console doctrine:database:create
````

Cela crée la base de données nommée "db_name_dev" que vous avez précisée dans la variable d'environnement DATABASE_URL, dans le fichier .env.dev.local.

Alors vous pouvez **créer les tables** dans la base de données avec la commande :

````
php bin/console doctrine:migrations:migrate
````

Répondre "yes" à la question : "WARNING! You are about to execute a database migration that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]:"

---

8. Charger les **données unitiales** pour le mode développement

Les données initiales sont chargées depuis le fichier [src/DataFixtures/AppFixtures.php](https://github.com/CarolineDirat/P8ImproveToDo/blob/master/src/DataFixtures/AppFixtures.php) grâce à la commande :

````
php bin/console doctrine:fixtures:load
````

Répondre "yes" à la question : "Careful, database "db_name_dev" will be purged. Do you want to continue? (yes/no) [no]:"

Il y a  3 utilisateurs, dont un administrateur avec le role "ROLE_ADMIN" (qui permet d'accéder à la gestion des utilisateurs) :

 username | password
----------|---------
  user1   | password
  user2   | password
  admin   | password

  Puis il y a 51 tâches dont 25 sont marquées comme faites. Certaines tâches ont pour auteur user1, d'autres user2, d'autres admin, et d'autres sans auteur.

 ---

 9. Vous pouvez désormais vous rendre à l'accueil du site, sur l'URI "/". Vous pouvez alors vous connecter en tant que user1, ou user2 ou admin pour accéder à la gestion des tâches. Seul l'utilisateur admin a accès à la gestion des utilisateurs (via le bouton "Admin" dans la barre de navigation).
