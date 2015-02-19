# Environnement de secours

## L'environnement

Sur l'ordinateur réservé aux TESTS, une préinstallation d'un environnement de secours permettra
de redémarrer l'application avec un minimum d'inconvénient
en cas de panne de l'ordinateur de production.


### Logiciels

Lors de chaque modification de l'environnement de production, une copie du logiciel est effectuée vers l'environnement de secours.
Les deux environnements sont donc toujours parfaitement identiques au niveau des *logiciels*.

### Données

Toutes les heures, un backup de la base de donnée et des documents utilisés par l'application (images, photos, documents PDF) est effectué.

Le backup est dans deux fichiers situés dans le répertoire

	/Application/mamstack/apps/prod/runtime/backup
	
Les deux fichiers sont

	nom-de-la-base-de-donnee.gz

et

	media.taz
	
Le premier fichier est un fichier compressé (gzip) de toutes les données dans la base de données.

Le second fichier est un fichier TAR compressé de tous les documents.


Ces deux fichiers sont backupés par Time Machine, et donc donc récupérable sur le disque de Time Machine.
Alternativement, on peut copier ces fichiers régulièrement en lieu sûr.

(Si on n'a pas de backup Time Machine, on n'a pas de backup de la production.)

## Restoration

Pour redémarrer sur l'environnement de secours il faut

  1. Récupérer les données de l'environnement de production, c'est à dire, récupérer les deux fichiers de copies de sauvegarde.
  2. Installer ces deux fichiers sur l'environnement de secours.


### Restoration des fichiers

#### Effacer les documents de l'environnement de secours

Dans le répertoire

	/Application/mamstack/apps/secours/web


supprimer les dossiers

	documents
	pictures


#### Restorer les documents de l'environnement de production

Dans le répertoire

	/Application/mamstack/apps/secours/web

Décompresser le fichier `media.taz` (`tar xzf media.taz`)

#### Restorer les privilèges

Dans une fenêtre terminal, en tant sur super administrateur, exécuter:

	chown -R daemon:daemon /Application/mamstack/apps/prod/web


### Restoration de la base de données

#### Effacer les données de l'environnement de secours

Se brancher dans la base de donnée, et supprimer toutes les tables.

Les données de connexion à la base de données sont dans le fichier

	/Application/mamstack/apps/secours/config/db.php

Le programme

	/Application/mamstack/apps/secours/runtime/etc/drop_all_table.sql
	
peut aider pour la suppression de toutes les tables.


#### Restorer les données de l'environnement de production

Décompresser le fichier `nom-de-la-base-de-donnee.gz`.

Importer le ficher résultant dans la base de donnée VIDE.

Le fichier résultant est un simple fichier sql qui "suffit" d'exécuter dans la base de donnée VIDE.
C'est tout.

## Accéder l'environnement de secours:

L'environnement de secours est accessible à l'adresse:

	http://imac-de-reunion.local/secours

Il a une bannière VERTE pour le distinguer de l'environnement de production (bannière noire), et de l'environnement de test (bannière rouge foncé).


## L'environnement de secours

L'environnement de secours est backupé de la même manière que l'environnement de production.

Ne pas oublier d'effectuer des backups Time Machine de l'environnement de secours.

## Retour à l'environnement de production

Lorsque l'environnement de production est à nouveau disponible, il faut en fait faire la procédure inverse,
partir du backup de l'environnement de secours, et le restaurer sur l'environnement de production.

Je conseille de pratiquer cette procédure 2 fois par an au minimum.


Je réalise cette procédure à chaque fois que je fais une mise à jour de l'environnement de production.

PM 16-FEV-2015
	