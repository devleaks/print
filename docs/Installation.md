# Rapport d'Installation

## Logiciels Installés

Les logiciels suivants ont été installé:

* /Applications/mamstack: Serveur "MAMP" + logiciel pour labo JJ Micheli
* Sequel Pro: Gestionnaire de base de données mysql
* Fraise: Editeur de texte
* Mou: Editeur de texte "markdown"

## Environnements

Deux environnements séparés ont été installés:

* DEVL: Tests et développements
* PROD: Production

Ils ont chacun une base de données séparée, et s'accèdent par deux URL distincts:

* DEVL: http:://imac-de-reunion.local:8080/print
* PROD: http:://imac-de-reunion.local:8080/prod


## Application en Test

L'application installée contient un petit environnement de test. Les utilisateurs suivants ont été créés:

* Admin, mot de passe "manager", role d'Administrateur de l'application.
* jjm, mot de passe "manager", role de gestionnaire du magasin.
* scott, mot de passe "tigerr", role d'employé réalisant les tâches issues des commandes.
* zahara, mot de passe "manager", futur role de la comptabilité avec les extractions et les tâches de clôture de fin d'année.

Le rôle d'administration de l'application permet de modifier des paramètres de l'application et de créer de nouveaux utilisateurs de l'application.


### Comptes Utilisateurs

L'application installée contient un petit environnement de test. Les utilisateurs suivants ont été créés:

* Admin, mot de passe "manager", role d'Administrateur de l'application.
* jjm, mot de passe "manager", role de gestionnaire du magasin.
* scott, mot de passe "tigerr", role d'employé réalisant les tâches issues des commandes.
* zahara, mot de passe "manager", futur role de la comptabilité avec les extractions et les tâches de clôture de fin d'année.

Soit un utilisateur par rôle.

### Roles

Le rôle d'administration de l'application permet de modifier des paramètres de l'application et de créer de nouveaux utilisateurs de l'application.

Le rôle de gestionnaire du magasin permet de faire toutes les tâches communes de la gestion quotidienne.

Le rôle de comptable regroupe les tâches liées aux factures, aux extractions des informations de l'application à destination de popsy, et à la clôture de l'année.

Le rôle d'employé permet de voir la liste des tâches à accomplir, et les détails à propos de ces tâches (voir les commandes liées aux tâches.) Il permet de prendre en charge les tâches et de signaler lorsqu'elles sont terminées, ou signaler un problème le cas échéant.

(Dans la version de test, les privilèges ne sont pas définis. Tout le monde peut tout faire.)

## Redémarrage du System

Lors de la prochaine visite, j'installerai et testerai le redémarrage automatique du système.

En attendant, si il faut redémarrer (ou arrêter) le système "à la main", allez dans le répertoire

/Application/mampstack

et démarrer le programme

manager-osx

Dans ce programme, sélectionner l'onglet "Manage Server".

On peut voir l'état des services dans le petit cadre. Si ils sont arrêtés, appuyer sur "Start All".

Si il y a un problème au redémarrage, il sera écrit dans l'onglet "Application log".

Il serait prudent de mettre en route Time Machine sur le serveur.