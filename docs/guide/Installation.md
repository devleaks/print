# Rapport d'Installation

## Logiciels Installés

Les logiciels suivants ont été installé:

* /Applications/mamstack: Serveur "MAMP" + logiciel pour Jo and Z
* Sequel Pro: Gestionnaire de base de données mysql
* Fraise et TextMate: Editeur de texte
* Mou: Editeur de texte "markdown"

## Environnements

Il y a deux machines distinctes préinstallées avec les logiciels de base.

1. imac comptoir: Machine de production.
2. imac "salle de réunion": Machine de tests.

### Machine de production (imac comptoir)

Sur la machine "de production" (imac comptoir), il n'y a qu'un seul environnement installé:
L'environnement de production utilisé pour les opérations quotidiennes.

* Production: http:://imac-de-imac/
* Production: http:://192.168.9.123/

### Machine de tests

Sur la machine de tests, il y a deux environnements installés:

#### Environnement de test

L'environnement de test proprement dit, remarqué par la bande supérieure de couleur rouge foncé.
Lorsque une nouvelle version est disponible, elle est d'abord installée là pour être mise à l'épreuve.

Cet environnement peut aussi être utilisé pour se familiariser avec l'application.

* Tests et développements: http:://imac-de-reunion.local/print
* Tests et développements: http:://192.168.9.105/print


#### Environnement de secours

L'environnement de secours est une copie exacte de l'environnement de production.

L'environnement de secours a une bande supérieure de couleur verte foncée.

L'environnement de secours utilise la même version du logiciel que celle utilisée sur l'environnement de production.
C'est une copie exacte.

Cet environnement ne devrait jamais être utilisé.
Il est installé, en secours, si une panne devait survenir à l'ordinateur du comptoir.
Il est vide de toute donnée, pour éviter une mauvaise manipulation.

* Secours: http:://192.168.9.105/prod

En cas de panne de l'ordinateur au comptoir, il faut récupérer les données d'un backup,
simplement installer les données sur l'ordinateur de secours,
et redémarrer les opérations sur l'ordinateur de secours.

Les données de backup sont de deux ordres:

1. Les données proprement-dites, dans la base de donnée.
2. Les images.

Le backup de la base de donnée est dans @app/runtime/backup.

Les images sont dans le dossier @app/web/pictures.

Il existe aussi

3. Les documents.

Mais il n'est pas indispensable de les recopier, puisque tous les documents peuvent être recréés à tout moment.

La procédure est simple, mais non automatisée.

Elle sera décrite dans un autre document.

## Application en Test

### Comptes Utilisateurs

L'application installée contient un petit environnement de test. Les utilisateurs suivants ont été créés:

* Admin, mot de passe "manager", role d'Administrateur de l'application.
* jjm, mot de passe "manager", role de gestionnaire du magasin.
* erwan, mot de passe "manager", role d'employé réalisant les tâches issues des commandes.
* zahara, mot de passe "manager", futur role de la comptabilité.

Soit un utilisateur par rôle.

Le rôle d'administration de l'application permet de modifier des paramètres de l'application et de créer de nouveaux utilisateurs de l'application.


### Roles

Le rôle d'administration de l'application permet de modifier des paramètres de l'application et de créer de nouveaux utilisateurs de l'application.

Le rôle de gestionnaire du magasin permet de faire toutes les tâches communes de la gestion quotidienne.

Le rôle de comptable regroupe les tâches liées aux factures, aux extractions des informations de l'application à destination de popsy, et à la clôture de l'année.

Le rôle d'employé permet de voir la liste des tâches à accomplir, et les détails à propos de ces tâches (voir les commandes liées aux tâches.) Il permet de prendre en charge les tâches et de signaler lorsqu'elles sont terminées, ou signaler un problème le cas échéant.

(Dans la version de test, les privilèges ne sont pas définis. Tout le monde peut tout faire.)


## Paramètres divers

### Dossiers

@app réfère au dossier d'installation de tout le code utilisé par l'application.

Les dossiers suivants sont importants:

@app/runtime: Dossier où sont stockés tous les documents non publics de l'application: Examples: Backup, extractions comptables, rapport quotidien des entrées, etc.

@app/web: Dossier accédé par l'application via le navigateur. Sont stockés sous ce dossier pour un accès facile via le web:
Les images associées aux commandes (dossier @app/web/pictures),
et tous les documents produits par l'application (dossier @app/web/documents):
Factures, notse de crédit, lettres de rappel.

## Documentation

La documentation est écrite en format markdown dans le répertoire docs/guide.

La documentation visible sur le site de l'application (onglet Aide) est générée
par le logiciel yii2/apidoc fourni avec le framekwork Yii et installé avec l'application.

```
vendor/bin/apidoc guide docs/guide ./web/help
```

