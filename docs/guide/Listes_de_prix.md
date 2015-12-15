# Listes de prix

L'application permet de générer des listes de prix à des fins de comparaison, ou de production d'autres documents
tels que les listes de prix diffusées aux clients.

Les prix reflétés dans les listes produites par l'application
sont les mêmes prix utilisés lors des commandes, puisque les mêmes _formules_ de calculs sont utilisées.

## Liste de prix simples

Les listes de prix "simples" concerne les articles individuels dont le prix dépend ou est calculé sur la base des dimensions de l'article commandé.

Tous les articles répondant à ces critères sont repris dans la _liste des prix_.

Presser l'icône de l'oeil pour voir la liste, ou l'icône de l'imprimante pour générer un PDF.

Sur la liste à l'écran, pour les articles dont les formules le permettent, il y a bouton _Ajuster les prix_
En pressant ce bouton, on révèle un petit panneau contenant les valeurs que l'on peut ajuster et qui influent sur le prix.
Seules les valeurs influençant le prix sont reprises.

On peut ajuster les paramètres et simuler toutes les variations de prix.
La modification des paramètres reste _fictive_ dans le sens ou les paramètres ne sont pas modifiés dans la base de donnée.

Le nom du paramètre modifié apparaît au dessus de sa valeur. C'est sont _nom de référence_, qui est unique.
La signification logique intuitive, si existante, du paramètre modifié apparaît au dessous de la valeur.

Lorsque les ajustements sont terminés et peuvent être appliqués, il faut noter les valeurs et modifier le prix des articles correspondants.
Cette démarche est explicite, étant donné son importance (modification des tarifs).

Le bouton Imprimer dans le bas de la page permet de générer un document PDF de la liste de prix pour diffusion.

Lors de la génération de l'impression, le nom de l'article dans le titre de la page est le `libelle_long` de l'article.

### Paramètres

Deux paramètres de l'application gouvernent les dimensions reprises dans la table.

Dans le domaine de paramètres `price_list`, les paramètres `width` et `height` ont les significations suivantes:

Format du paramètre: Trois valeurs séparées (nombre entier) par une virgule:

	min,max,step

Ce qui signifie que les largeurs (`width`, respectivement hauteurs, `height`), prendront les valeurs courant de la valeur `min`, à la valeur `max` par pas de `step`.

Exemple:

	price_list.width=30,160,20

signifie que les largeurs iront de 30 à 160cm par pas de 20cm: 30, 50, 70... 150.

## Listes de prix "composites"

Les listes de prix composites permette de juxtaposer des listes de prix pour des articles différents, ou pour des articles "combinés" entre eux,
comme par exemple, une impression et un encadrement.

Pour créer une liste composite, il faut lui donner un nom, et les tailles pour lesquelles on prépare le tableau.

Les tailles doivent être présentées sous la forme d'une lite de valeurs séparées par des virgules, et où chaque valeur est sous la forme _largeur_ &times; _hauteur_:

	w1xh1,w2xh2

où w1, h1, w2, h2 sont les dimensions souhaitées en cm, et `x` est la lettre x minuscule. Exemple:

	30x40,60x90,110x170

présentera le tableau pour des articles aux dimensions 30 &times; 40cm, 60 &times; 90cm, et 110 &times; 170cm.

### Composition des colonnes d'articles

Lorsque la liste est créée, il faut ajouter des articles dans chaque colonne.

Pour ajouter un article, le sélectionner dans la liste déroulante, sous la description de la liste des prix
et lui donner une `position`. Sans position, l'article est refusé.

Une position est une colonne pour les prix d'un article.
La position est un nombre entier arbitraire.
Les colonnes d'articles sont juxtaposées dans l'ordre de leur position.

Tous les articles peuvent être ajoutés de la sorte.

A tout moment, pour voir la liste composée, il suffit de presser le bouton Voir.

Pour créer un article, ou un prix, pour une combinaison d'articles, comme par exemple une impression et un encadrement,
il suffit de placer les deux (ou trois, ou quatre, sans limite de quantité) articles dans la même `position`.
Tous les articles placés à une même position (c'est à dire dans la même colonne) seront additionnés.
L'entête de la colonne reflètera tous les articles placés dans cette colonne: Nom du premier article + nom du deuxième article + etc.

Les articles placés dans une même colonne seront combinés, dans la limite du possible, en respectant les règles métiers imposées.
Par exemple, un renfort placé sur un ChromaLuxe est placé 10cm à l'intérieur du bord de l'article,
alors que le même renfort est placé à l'intérieur à 5cm du bord sur d'autre support.
De plus, certains encadrements nécessitent de la place pour leur ajustement,
imposant d'installer les renforts à d'autres positions à l'intérieur de l'article.
Dans d'autres cas, par exemple lorsqu'un cadre est présent, les renforts sont gratuits au dessus de certaines dimensions.
Ce genre de combinaison est difficile à anticiper les prix affichés ne peuvent parfois pas être évalué en tenant compte de tous les paramètres.

Pour supprimer un article, presser l'icône de la poubelle dans la liste.
Pour supprimer une colonne dans la liste des prix, supprimer tous les articles de cette colonne (ou à cette position).

### Impression

Le bouton Imprimer dans le bas de la page permet de générer un document PDF de la liste de prix pour diffusion.

Lors de la génération des tableaux, le nom de l'article dans le titre de la colonne est le `libelle_court` de l'article.
