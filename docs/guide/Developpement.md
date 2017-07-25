# Notes à propos des Développements

## Logiciels Utilisés

L'application a principalement été développée dans le language PHP,
avec plusieurs interactions directement écrites en JavaScript.

Les logiciels suivants ont été utilisé pour le développement de l'application:

* [Yii Framework](http://www.yiiframework.com) (Version 2) - Framework d'applications pour PHP.
* Pile d'exécution [Bitnami MAMP stack](https://bitnami.com/stack/mamp) (MacOS - Apache - Mysql - PHP)

### Autres Librairies

De nombreuses librairies, souvent intégrées à l'environnement Yii ont aussi été utilisées:

* [Highcharts](https://www.highcharts.com) - Librairie graphiques
* Dc.js - [Document Charting](https://dc-js.github.io/dc.js/), librairie de graphiques d'analyse (avec [Crossfilter](http://crossfilter.github.io/crossfilter/) et [D3](https://d3js.org))

## Codes Sources

Tous les codes sources ont été consignés depuis le début sur github.

L'adresse du repository est https://github.com/devleaks/print

(Utilisateur "devleaks", nom de repository "print").

Le repository est en accès libre.

(L'application est pratiquement inutilisable sans données de base, qui elles ne sont pas disponibles publiquement.)

L'installation locale du logiciel se fait par les procédures standards de github et Yii.

```
git clone https://github.com/devleaks/print
cd print
composer install
```

Le développeur doit être familier avec les environnements PHP, MySQL, JavaScript, et Yii.
La base de donnée utilisée est MySQL mais n'importe quelle base de donnée pourrait être utilisée,
à partir du moment ou elle est intégrée au framework Yii.

Pour des raisons de sécurité, il n'y a pas de script de création de la base de donnée,
il faut partir d'une base de donnée existante.
Une copie de sauvegarde vide de base de donnée (format MySQL) est disponible dans le répertoire runtime/etc/emptydb.sql.


## Mises à Jour

Lors d'ajout de fonctions ou de correction de problème, tous les composants sous-jacents utilisés
ont régulièrement été maintenus à jour.

Toutes les mises à jours (["commits"](https://github.com/devleaks/print/commits/master)) sont documentées sur github, depuis le début de la création du logiciel.
Il suffit de s'en référer pour suivre l'évolution du logiciel, les problèmes corrigés, les évolutions demandées...
(Avec `git`, il est possible, à tout moment, de revenir à une version antérieure consignée.)


## Notes sur les versions de PHP

En deux ou trois ans, le language PHP a énormément évolué.
Les premiers développements utilisaient la versiob 5.X du PHP.
Tous les environnements de production restent sous ces versions.

Les derniers développements sont réalisés avec les dernières versions du PHP (7.X et au delà).
Les développements sont maintenus compatibles avec la version de PHP des environnements de production (5.X).

Il est recommandé, un jour, de:

1. Mettre à jour le système d'exploitation des iMac. (Tout est impactés: Applications, imprimantes, Messagerie, etc.)
1. Installer une nouvelle version de la pile Bitnami MAMP. Elle peut coexister avec la version actuelle, à condition que la version actuelle fonctionne sous la nouvelle version du système d'exploitation.
1. Mettre à jour les bases de données. Peut se réaliser par copie pour préserver la copie originale.
1. Configurer l'application pour utiliser ces dernières versions. Peut se réaliser par copie pour préserver la copie originale.
1. S'assurer que toutes les opérations de routine continuent de fonctionner (backup, copies de sauvegardes, transferts entre ordinateurs...)

Une telle mise à jour requiert approximativement 1 jour complet de travail par machine, tests sommaires inclus.
Cette mise à jour doit être réalisée avec beaucoup de précautions: Copies de sauvegardes sûres avant d'entamer les mises à jours,
tests de chaque composant mis à jour, et tests finaux.

Le risque est minimisé par le fait que deux ordinateurs sont disponibles, et peuvent être mis à jour indépendamment,
en ne mettant à jour le second que lorsque tout aura été vérifié sur le premier.

Cette opération devrait être répétée approximativement tous les 2 ans pour maintenir la pérénité de l'application et de l'informatique dans son ensemble.

Le danger est de ne plus pouvoir utiliser les nouvelles versions des logiciels installés,
versions qui resteraient figées sans possibilités d'évolution et sans correction de problèmes éventuels dans ces modules.

## En cas de problème

En cas de problème avec le logiciel (erreur) sur l'environnement de production,
un message electronique est immédiatement envoyé à l'adresse mentionnée dans le fichier de configuration de l'application.
De ce fait, le ou les développeurs sont avertis immédiatement d'un dysfonctionnement et de la _trace_ de l'erreur.
Ils peuvent entreprendre des mesures correctrices pour éviter les dysfonctionnements futurs.
Souvent cependant, les données originales sont nécessaires pour reproduire le dysfonctionnement,
ce qui rend la recherche des erreurs parfois impossible en l'absence des données de production originale et à jour.

Une trace du problème est aussi maintenue sur le serveur d'application (répertoire runtime/logs).
Toutes les erreurs produites en production sont disponibles à cet endroit pour référence ultérieure.
