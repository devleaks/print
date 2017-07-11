# Notes à propos des Développements

## Logiciels Utilisés

L'application a principalement été développée dans le language PHP,
avec plusieurs interactions directement écrites en JavaScript.

Les logiciels suivants ont été utilisé pour le développement de l'application:

* [Yii Framework](http://www.yiiframework.com) (Version 2) - Framework de développement pour PHP.
* Pile d'exécution [Bitnami MAMP stack](https://bitnami.com/stack/mamp) (MacOS - Apache - Mysql - PHP)


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

## Mises à Jour

Lors d'ajout de fonctions ou de correction de problème, tous les composants sous-jacents utilisés
ont régulièrement été maintenus à jour.

Toutes les mises à jours (["commits"](https://github.com/devleaks/print/commits/master)) sont documentées sur github, depuis le début de la création du logiciel.

## Notes sur les versions de PHP

En deux ou trois ans, le language PHP a énormément évolué.
Les premiers développements utilisaient la versiob 5.X du PHP.
Tous les environnements de production restent sous ces versions.

Les derniers développements sont réalisés avec les dernières versions du PHP (7.X et au delà).
Les développements sont maintenus compatibles avec la version de PHP des environnements de production (5.X).

Il serait opportun, un jour, de

1. Mettre à jour le système d'exploitation des iMac. (Tout est impactés: Applications, imprimantes, Messagerie, etc.)
1. Installer une nouvelle version de la pile Bitnami MAMP. Peut coexister avec la version actuelle.
1. Mettre à jour les bases de données. Peut réaliser par copie pour préserver la copie originale.
1. Configurer l'application pour utiliser ces denrières versions. Peut réaliser par copie pour préserver la copie originale.

Une telle mise à jour requiert approximativement 1 jour complet de travail par machine, tests sommaires inclus.
Cette mise à jour doit être réalisée avec beaucoup de précautions: Copies de sauvegardes sûres avant d'entamer les mises à jours,
tests de chaque composant mis à jour, et tests finaux.