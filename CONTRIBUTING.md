# Comment contribuer au projet P8ImproveToDo ?

## Etape 1 : Dupliquer le dépôt original via le Fork

Rendez-vous simplement sur la page du dépôt (repository) **CarolineDirat/P8ImproveToDo** pour lequel vous souhaitez apporter votre contribution, puis cliquez sur le bouton "**Fork**" en haut à droite.

Le dépôt est alors dupliqué dans votre compte : remarquez que le chemin du dépôt dupliqué n’est pas le même que celui d’origine, il est préfixé par le nom de votre compte.

## Etape 2 : Cloner le dépôt dupliqué en local

    git clone https://github.com/VotreNomDeCompteGitHub/P8ImproveToDo

Et suivre les instructions d'installation du fichier README.md

## Etape 3 : Créer une branche spécifique à votre contribution, depuis la branche master

En effet, la branche master doit rester "la même" que la branche master du projet source. (voir la dernière partie de ce document : "Mettre à jour le projet dupliqué")

Veillez à ce que votre branche de contribution ait un nom descriptif de la contribution apportée au projet.

    git switch master
    git checkout -b ma-branche-de-contribution

## Etape 4 : Réaliser votre contribution au projet

Vous réaliserez votre contribution au projet **sur votre branche de contribution**, tout en suivant le **processus de qualité** du projet. Et effet, un certain nombre de règles sont à respecter, et pour cela, des outils sont disponibles dans le projet.

### Les règles à respecter

#### Respecter les [meilleures pratiques de Symfony](https://symfony.com/doc/current/best_practices.html)

#### Commentez le code du dossier src/ via [PHPDoc](https://docs.phpdoc.org/3.0/guide/index.html)

dans des DocBlocks précédant les méthodes, et éventuellement les classes.

#### Versionner le projet avec Git

Et veuillez à expliciter les messages des commits.

#### Respecter les normes de codage PHP ([PSR-1](https://www.php-fig.org/psr/psr-1/), [PSR-12](https://www.php-fig.org/psr/psr-12/)) et celles de [Symfony](https://symfony.com/doc/current/contributing/code/standards.html) grâce à l'outil [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

Pour cela, télécharger le fichier [php-cs-fixer.phar](https://cs.symfony.com/download/php-cs-fixer-v2.phar) à la racine du projet, et lancez-le avec la commande :

    php php-cs-fixer.phar fix

Cet outil va non seulement repérer les problèmes de normes de codage (en s'appuyant sur le fichier de configuration **.php_cs.dist** à la racine du projet), mais il va aussi les corriger pour vous.

| Veuillez ne pas modifier le fichier de configuration .php_cs.dist|
|---------------------------------------------------------------------------|

## Processus de qualité

### L'outil PHPStan

L'outil PHPStan est installé comme dépendance sur le projet, en mode développement. Ainsi, au cours du développement, vous pouvez détecter des bugs issus d'erreurs de codage grâce à la commande :

    ./vendor/bin/phpstan analyse <dossier>

Les autres configurations de la commande sont dans le fichier de configuration **phpstan.neon**.

Je vous conseille d'**analyser les dossiers** src/ et tests/ **séparemment** :

    ./vendor/bin/phpstan analyse src

et

    ./vendor/bin/phpstan analyse tests

**Autre remarque :**
Lancer les tests PHPUnit (voir ci-après) au moins une fois avant de lancer PHPStan sur le dossier tests/ (afin de générer l'exécutable de phpunit dans le dossier vendor/bin/, pour que phpstan ait accès à l'autoload de PHPUnit - voir ligne 7 de phpstan.neon).

|Veuillez à **ne pas modifier** le fichier de configuration **.phpstan.neon**|
|----------------------------------------------------------------------------|

### Les tests unitaires et fonctionnels via PHPUnit

#### Les tests de PHPUnit doivent rester valides

La commande suivante lance les tests unitaires et fonctionnels via PHPUnit

    ./vendor/bin/simple-phpunit

Votre code ne doit provoquer aucun échec des tests existants.

#### Tester votre code via PHPUnit

Il est recommandé de tester le code du dossier **src/**, via des tests unitaires et fonctionnels écrits dans le dossier **tests/**, et en particulier pour les valeurs critiques.

Vous pouvez tester les méthodes des classes du dossier **src/** en créant les classes de test correspondantes dans le dossier **tests/**. Pour cela, veuillez suivre la même architecture d'espace de nom que dans le dossier src/.
*Par exemple, la classe User du namespace App\Entity est testée dans le dossier tests/ par la classe UserTest avec la namespave App\Tests\Entity.*

La couverture de code doit être supérieure à 70%.

**Comment tester un application Symfony ?** Voir la [documentation officielle](https://symfony.com/doc/current/testing.html).

|**Veuillez ne pas modifier le code des tests déjà écrits.**|
|-----------------------------------------------------------|

### Les tests fonctionnels de Behat doivent rester valides

Il faut **d'abord** lancer selenium qui va gérer les étapes qui nécessitent JavaScript, avec la commande :

    java -jar selenium.jar

**Puis**, lancez les tests fonctionnels de Behat avec la commande :

    ./vendor/bin/behat

#### Vous pouvez ajouter des tests fonctionnels via Behat dans le dossier features/

Pour savoir comment faire, je vous conseille la [documentation de Behat](https://docs.behat.org/en/latest/guides.html#).

|**Mais veuillez ne pas modifier le code des tests déjà écrits, dans le dossier features/.**|
|-------------------------------------------------------------------------------------------|

### Donc, avant de passer à l'étape suivante

Il faut vous assurer que votre code respecte le processus de qualité du projet. Pour cela, tout doit être valide pour **phpstan**, **php-cs-fixer**, **phpunit** et **behat** :

    ./vendor/bin/phpstan analyse src --level=6
    ./vendor/bin/phpstan analyse tests --level=6

    php php-cs-fixer.phar fix

    ./vendor/bin/simple-phpunit

    java -jar selenium.jar
    ./vendor/bin/behat

## Etape 5 : Proposez votre contribution via la "pull request"

### 5.1. Pousser sur le dépôt dupliqué

**Une fois les processus de qualité validés**, poussez le code de votre branche de contribution sur le dépôt dupliqué :

    git push origin ma-branche-de-contribution

Maintenant, sur votre projet dupliqué sur GitHub, vous pouvez voir que GitHub a remarqué que nous avons poussé une nouvelle branche et affiche un gros bouton vert pour vérifier nos modifications et ouvrir une requête de tirage (une "pull request") sur le projet original.

### 5.2. Ouverture d'une pull request

Pour le moment, votre contribution n’est disponible que sur votre copie du dépôt. Pour faire en sorte de proposer vos évolutions au propriétaire du dépôt source, il faut utiliser la "pull request" de GitHub (ou requête de tirage). Cela permet de notifier le propriétaire que vous avez une proposition de contribution.

**Cliquer le bouton vert "Compare & pull request"** : une fenêtre vous permet de créer un **titre** et une **description** de la modification que vous souhaitez faire intégrer pour que le propriétaire du projet trouve une bonne raison de la prendre en considération. C’est généralement une bonne idée de passer un peu de temps à écrire une description en l'argumentant autant que possible. Ainsi, le propriétaire saura pourquoi la modification est proposée et en quoi elle apporterait une amélioration au projet.

Pour valider la création de la pull request, après avoir renseigné le titre et la description, **cliquer sur le bouton "Create pull request"** : le propriétaire du projet que vous avez dupliqué reçoit alors une notification qui lui indique que quelqu’un suggère une modification. Il pourra alors se rendre sur la pull request qui est aussi crée sur le dépôt source, et lui donnant accès à toutes les informations disponibles dans une pull request.

## Etape 6 : Valider les intégrations continues

L'ouverture de la pull request déclenche les vérifications par les intégrations continues du projet :

- **Codacy Static Code Analysis** qui évalue la qualité de code (dont la complexité et la présence de duplications)

- **Github Action** qui lance trois vérifications (d'après les fichiers de configuration dans .github/workflows/) avec :
  
  - **static_analysis/Phpstan_src** qui lance phpstan sur le dossier src/
  - **static_analysis/Phpstan_tests** qui lance phpstan sur le dossier tests/
  - **CI/security_checker** qui vérifie la sécurité des dépendances

- **ci/gitlab/gitlab.com** qui lance les tests PHPUnit, d'après la configuration dans le fichier .gitlab-ci.yml

Le résultat des vérifications vous donne une idée du respect du processus de qualité décrit ci-avant.

Heureusement, tant que la pull request est ouverte, vous avez la possiblité d'ajouter de nouvelles validations (commits).

Lorsqu'une intégration continue n'est pas validée, cliquer sur le lien *Détails* de celle-ci pour obtenir les informations sur cet échec de vérification. Sur votre branche de contribution, en local, effectuez les modifications nécessaires pour régler le problème. Poussez à nouveau la branche de contribution, ce qui relancera les intégrations continues.

**Faites votre possible** pour que **toutes les vérifications** des intégrations continues **passent au vert** (avec la mention "All checks have passed") **avant de passer à l'étape suivante :**

- Les analyses par **phpstan** des dossiers src/ et tests/, lancée par Github Action, ne doivent générer aucune erreur ou warning.
- Les tests **PHPUnit** lancés sur gitlab doivent tous réussir.
- Il est possible que **Codacy** vous donne du fil à retordre, l'important est d'optimiser le code PHP, CSS, HTML et Javascript.

| Veuillez ne pas modifier les fichiers de configuration des intégrations continues : .gitlab-ci.yml, phpstan-ci-src.neon, phpstan-ci-tests.neon et les fichiers dans .github/workflows. |
|----|

## Etape 7 : Itérations sur la pull request

**Une fois la pull request ouverte, vous avez la possibilité de discuter avec le propriétaire.**

En effet, le propriétaire du projet peut regarder les modifications suggérées et les fusionner ou les rejeter ou encore les commenter.

**Par exemple**, supposons que le propriétaire trouve la contribution intéressante, mais qu'il ne l'accepte pas telle quelle. Il peut alors commenter une ou plusieurs lignes de votre code puis laisser un commentaire général dans la section de discussion. Les commentaires de code sont aussi publiés dans la conversation.

Une fois que le mainteneur a commenté, la personne qui a ouvert la requête de tirage (et en fait toute personne surveillant le dépôt) recevra une notification.

Maintenant, le contributeur sait ce qu’il doit faire pour que ses modifications soient intégrées. Il lui suffit d'effectuer les correctifs sur la branche de contribution, de les valider (commits) et de repousser à nouveau la branche sur le dépôt dupliqué :

    git push origin ma-branche-de-contribution

Le propriétaire du projet sera notifié à nouveau des modifications du contributeur et pourra voir que les problèmes ont été réglés quand il visitera la page de la requête de tirage. En fait, comme la ligne de code initialement commentée a été modifiée entre temps, GitHub le remarque et fait disparaître la différence obsolète.

Maintenant, le propriétaire peut soit continuer la discussion, soit rejeter la contribution en fermant la pull request, soit l'accepter en mergeant la branche de contribution dans la branche master.

## Etape 7 : Le propriétaire du projet fusionne (merge) ou ferme (close) la requête de tirage

Pour résumer le processus de contribution, une branche de contribution est créée sur le projet dupliqué, une pull request est ouverte dessus, une discussion s’engage, du travail additionnel peut être ajouté sur la branche et à la fin, la requête est soit fermée, soit fusionnée par le propriétaire du projet source.

## Et en cas de conflits

### Synchronisation de la branche master mise à jour avec celle de votre projet dupliqué

Si votre requête de tirage devient obsolète ou ne peut plus être fusionnée proprement, il vaut mieux la corriger pour que le mainteneur puisse la fusionner facilement. GitHub testera cela pour vous et vous indique sur la requête de tirage si la fusion automatique est possible ou non.

Si vous voyez quelque chose comme "La requête de tirage ne peut pas être fusionnée proprement", il vous faut corriger votre branche pour qu’elle ait un statut "vert" et que le mainteneur n’ait pas à fournir de travail supplémentaire.

Une méthode conseillée est de fusionner la branche cible dans votre branche de contribution :

1. Ajouter le dépôt original comme nouveau dépôt distant.
2. Récupérer la branche cible que vous fusionnerez dans votre branche de contribution
3. Corriger les conflits
4. Pousser la branche de contribution sur la même branche de contribution pour laquelle vous avez ouvert la requête de tirage.

**Par exemple**, considérons que l’auteur original de P8ImproveToDo ait fait des modifications qui créent un conflit dans votre requête de tirage. Pour l'exemple, on nomme **contrib** la branche de contribution.

Examinons les étapes à réaliser pour régler les conflits :

(1) **Ajouter le dépôt original comme dépôt distant sous le nom « upstream ».**

    git remote add upstream https://github.com/CarolineDirat/P8ImproveToDo 

(2) **Récupère les derniers travaux depuis ce dépôt distant.**

    $ git fetch upstream 
    remote: Counting objects: 3, done.
    remote: Compressing objects: 100% (3/3), done.
    Unpacking objects: 100% (3/3), done.
    remote: Total 3 (delta 0), reused 0 (delta 0)
    From https://github.com/CarolineDirat/P8ImproveToDo
    * [new branch]      master     -> upstream/master

(3) **Fusionner la branche principale dans la branche de contribution**:
C'est-à-dire que l'on fusionne les modifications de la branche par défaut en amont - dans ce cas, upstream/master - dans votre branche locale par défaut. Cela synchronise la branche par défaut de votre fork avec le référentiel en amont, sans perdre vos modifications locales.

    $ git merge upstream/master
    Auto-merging fichier.php
    CONFLICT (content): Merge conflict in fichier.php
    Automatic merge failed; fix conflicts and then commit the result.

(4) **Correction des conflits créés** (ici, dans fichier.php)

(5) **Valider les modifications apportées (commit)**

    $ git commit
    [contrib 3c8d735] Merge remote-tracking branch 'upstream/master' \
        into contrib-bis

(6) **Pousse sur la même branche de contribution.**

    $ git push origin contrib
    Counting objects: 6, done.
    Delta compression using up to 8 threads.
    Compressing objects: 100% (6/6), done.
    Writing objects: 100% (6/6), 682 bytes | 0 bytes/s, done.
    Total 6 (delta 2), reused 0 (delta 0)
    To https://github.com/VotreNomDeCompte/P8ImrpoveToDo
    ef4725c..3c8d735  contrib-bis -> contrib

La requête de tirage est alors automatiquement mise à jour et un nouveau contrôle est effectué par GitHub pour vérifier la possibilité de fusion.

## Mettre à jour le dépôt dupliqué

Une fois que vous avez dupliqué un dépôt GitHub, votre dépôt (votre « copie ») existe indépendamment de l’original. En particulier, lorsque le dépôt original a de nouveaux commits, GitHub vous en informe avec un message comme :

    This branch is 5 commits behind carolinedirat:master.

Mais votre dépôt dupliqué GitHub ne sera jamais mis à jour automatiquement par GitHub; c’est quelque chose que vous devez faire vous-même.

- **Une possibilité pour faire ça ne requiert aucune configuration.**
  
**Par exemple**,  vous avez dupliqué depuis <https://github.com/CarolineDirat/P8ImproveToDo>, vous pouvez garder votre branche master à jour comme ceci :

    git checkout master (1)
    git pull https://github.com/CarolineDirat/P8ImproveToDo (2)
    git push origin master (3)

(1) Si vous étiez sur une autre branche, basculer sur master.
(2) Récupérer les modifications depuis <https://github.com/CarolineDirat/P8ImproveToDo> et les fusionner dans master.
(3) Pousser votre branche master sur origin.

Cela fonctionne, **mais c’est un peu fastidieux** d’avoir à épeler l’URL de récupération à chaque fois.

- **Vous pouvez automatiser ce travail avec un peu de configuration :**

```git
git remote add todo https://github.com/CarolineDirat/P8ImproveToDo (1)
git branch --set-upstream-to=todo/master master (2)
git config --local remote.pushDefault origin (3)
```

(1) Ajouter le dépôt source et lui donner un nom : **todo**.
(2) Paramétrer votre branche master pour suivre la branche master du dépôt distant **todo**.
(3) Définir le dépôt de poussée par défaut comme étant origin.

**Une fois que cela est fait, le flux de travail devient beaucoup plus simple :**

    git checkout master (1)
    git pull (2)
    git push (3)

(1) Si vous étiez sur une autre branche, basculer sur master.
(2) Récupérer les modifications depuis **todo** (lié au dépôt source) et les fusionner dans master.
(3) Pousser votre branche master sur origin.

Cette approche peut être utile, mais elle n’est pas sans inconvénient. **Git** fera ce travail pour vous gaiement et silencieusement, mais **il ne vous avertira pas si vous faites un commit sur master**. Vous devrez donc prendre garde à **ne jamais faire de commit directement sur master**, puisque cette branche appartient effectivement au dépôt en amont.

## Pour en savoir plus

Voici des sources qui illustrent visuellement et approfondissent comment contribuer à un projet open source sur GihHub :

- <https://git-scm.com/book/fr/v2/GitHub-Contribution-%C3%A0-un-projet>
- <http://codeur-pro.fr/contribuer-a-un-projet-open-source-sur-github/>
- <https://blog.zenika.com/2017/01/24/pull-request-demystifie/>
