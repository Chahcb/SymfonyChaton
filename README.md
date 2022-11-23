# Cours Symfony

développé en PHP 8.1

### Prérequis

* [Composer](https://getcomposer.org/), c’est mieux, même si symfony en installe une version allégée si vous ne l’avez
  pas
* [Git](https://git-scm.com/)
* [PHP](https://www.php.net/manual/fr/intro-whatis.php)

### Installation

* Installer le l'executable [Symfony](https://symfony.com/)

### Commande

* savoir la version php utilisée `php -v`
* Créer un projet `➜  ~ symfony new --webapp NomDeMonProjet`
* Lancer le serveur `➜ symfonyCours git:(main) symfony server:start`
* Créer un controller `➜ symfonyCours git:(main) ✗ symfony console make:controller`
* Créer une entité `➜ symfonyCours git:(main) ✗ symfony console make:entity`
* Créer une migration `➜ symfonyCours git:(main) ✗ symfony console doctrine:migrations:migrate`
* Créer un formulaire type pour une entité donnée. Ce formulaire type est utilisé par les controllers pour générer les
  vues d’ajout/modification/suppression `➜ symfonyCours git:(main) ✗ symfony console make:form`

### Utilisation

Symfony fournit un serveur intégré pour travailler. Pour le lancer, taper la commande `symfony server:start` à partir du
dossier de votre projet. Le serveur se lance par défaut sur **_http://127.0.0.1:8000_**. Si un projet symfony a déjà été
lancé alors le deuxième projet se lance sur **_http://127.0.0.1:8001_**