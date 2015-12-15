# Notes à propos de l'application

A cause de sa conception itérative, et de l'ajout de fonctions au fil du temps, certaines entités ont été négligées.

Il serait intéressant de corriger ces entités avant que l'application ne se développe encore.

Les deux entités principales sont

1. Les clients
2. Les articles

Ces deux entités devraient être apurées.

## Articles

Supprimer les colonnes non utilisées.

Ajouter quelques colonnes fonctionnelles (telle que `YII_CATEGORY`).

Renommer les colonnes utilisées avec des noms standards anglais, en accordance avec l'application.

Faire le lien avec la table des personnes (voir ci-dessous).

## Clients

Faire évoluer la table vers une entité plus générale (people) pour inclure

1. Les clients
2. Les fournisseurs

Toutes les entités avec lesquelles l'application communique.

Renommer les colonnes utilisées avec des noms standards anglais, en accordance avec l'application.

## Normalisations

Les tables clients et articles ne sont pas normalisées (rien).

On peut envisager de normaliser les éléments suivants:

### Clients

  * Communes (localités),
  * pays.

### Articles

  * Catégories d'articles
  * Fournisseurs

## Note

En dehors de ces imperfections, l'application s'intègre bien avec le cadre de développement Yii,
respecte les conventions, et usages des entités de Yii.

De très nombreux packages ont été ajoutés, de Mpdf à Highcharts, en passant par la majorité des packages
développés par M. Kartik Visweswaran.