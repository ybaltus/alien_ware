# Alien-Where

## Introduction
Boutique en ligne de ventes de produits PC.

## Pré-requis
Pour lancer le projet vous aurez besoin de la configuration suivante :
* [Composer v1 ou v2](https://getcomposer.org/)
* [MariaDB](https://mariadb.com/kb/en/where-to-download-mariadb/#the-latest-packages) >= v10.2
* [Php](https://www.php.net/manual/fr/install.php) >= v7.2.5
* [Nodejs >= v14](https://nodejs.org/en/download/) + [Yarn >= v1.22 (préférence)](https://yarnpkg.com/getting-started/install) ou [Npm >= v6.14](https://www.npmjs.com/)

> [Aide Symfony LAMP](https://codereviewvideos.com/course/symfony-deployment/video/symfony-4-lamp-stack)

> [Aide SymfonyMAMP](https://blog.gary-houbre.fr/developpement/mamp-comment-bien-installer-notre-projet-symfony-sur-mac)

## Stack technique
* [Symfony 5.3](https://symfony.com/doc/current/setup.html)
* [Twig](https://twig.symfony.com/)
* [Bootstrap 4](https://getbootstrap.com/)
* [JQuery 3](https://jquery.com/)
* [Webpack encore](https://symfony.com/doc/current/frontend.html)

## Pour initialiser le projet

### 1/ Configurer la base de données
Créer son fichier ".env.local" qui contiendra les informations de connexion avec sa base de données. Sinon les commandes suivantes ne pourront pas fonctionner ! Vous pouvez copier coller le ".env" existant.
> Exemple avec MariaDB: DATABASE_URL=mysql://db_user:db_password$@127.0.0.1:3306/alien_where?serverVersion=10.2.10-MariaDB

### 2/ La liste des commandes à exécuter
```
1. Initialiser les packages composer: composer install 
2. Créer la BDD: php bin/console doctrine:database:create ou symfony console doctrine:database:create
3. Mettre à jours la BDD: php bin/console doctrine:migrations:migrate ou symfony console doctrine:migrations:migrate
4. Installer les modules node: yarn install --force ou npm install --force
5. Compiler les assets: yarn encore dev npm run dev (Plus d'infos sur https://symfony.com/doc/current/frontend/encore/simple-example.html)
6. Lancer le sereur web local: symfony console server:start ou php/bin console server:start
```
