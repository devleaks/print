# Articles

Pour la gestion de l'application, une nouvelle catégorie appellée `yii_category` a été ajoutée pour distinguer les différents types d'article.

La catégorie existante est utilisée aussi.

Pour les articles, le placement dans l'une ou l'autre catégorie a donc une influence sur le déroulement du programme.


## Catégories `yii_category`

La cagétogie `yii_category` a été créée pour répondre aux besoins spécifiques de l'application.
Elle permet de distinguer les articles suivants:

Catégorie | Utilisation
---------:|:-------------
SPECIAL   | Catgorie pour les articles spéciaux, tels que remise,  surcoût, etc. NE PAS MODIFIER.
Tirage| Articles de type tirage, impression... La colonne 'Catégorie' (voir ci-dessous) est importante pour 
ChromaLuxe|Article ChromaLuxe (1 seul)
Cadre|Article de type Cadre
UV|Article de type protection UV (1 seul)
Renfort|Article de type refort (1 seul)
Support|
Canvas|
Chassis|
Vernis de protection|
MontageParam|
SupportParam|
ChromaParam|
RenfortParam|
TirageParam|
CadreParam|
ChromaType|
ProtectionParam|
Divers|


## Catégories `Categorie`

Cette catégorie est utilisée pour subdiviser les articles en sous-catégories.
Pour l'instant, elle est utilisée pour distinguer les types de tirage:

En fonction des types de Tirages suivants, les options sont ajustées:

Catégorie | Utilisation
---------:|:-------------
Papier Type Photo | Papier Type Photo
Papier Fine Art | Impression sur papier type Fine Art
Canvas | Tirage sur canvas

Toutes les autres valeurs de Tirages ouvrent toutes les options.


# Articles dont le prix est calculé

Sélectionner un dont le prix est calculé aura pour effet:

* D'ajuster le libellé de cet article,
* De révéler un panneau où l'on précisera les "options" de cet article spécial.

Le but de ce panneau est de recueillir les options pour cet article et de composer le prix de cette ligne d'article.

Le prix des options sera décomposé dans le panneau, et le prix total de l'article sera reporté dans la ligne d'article.

## ChromaLuxe

Pour les articles ChromaLuxe les paramètres suivent doivent être fournis pour fixer le coût:

* Dimensions de l'article,
* Type de ChromaLuxe.

Accessoirement, les options suivantes peuvent être ajoutées:

* Cadre
* Renfort
* Montage du cadre

Selon certaines règles, les renforts seront parfois ajoutés sans coût aux options.

Pour chacune de ces options, il y a un coût calculé associé.

Les formules et paramètres du calcul du coût seront détaillés dans un autre document.
Les formules ne peuvent être modifiées que par un programmeur, mais les paramètres de ces formules
(par exemple des coûts partiels) peuvent être ajustés via l'application.


## Fine Art

Pour les articles de type Fine Art, les paramètres suivent doivent être précisés:

* Type de tirage (papier, canvas…)

Accessoirement, les options suivantes peuvent être ajoutées:

* Tirage de type Papier Photo: Choix de la finition mate ou brillante (sans coût).

* Tirage de type Papier Fine Art: Précision du papier choisi en note, couche de protection.

* Tirage sur canvas: Cadre de canvas.



## Article libre

Pour l'article libre, libellé Article divers, il faut entrer:

* Un libellé qui sera repris en tant que note sur la ligne de commande,
* Un prix unitaire,
* Le taux de TVA applicable pour cet article.



## Remise

L'article Remise est une ligne d'article normale mais qui a pour effet d'appliquer une remise ou un surcoût sur la totalité de la commande.

Le surcoût ou la remise peut être exprimé par une valeur fixe, ou un pourcentage des autres lignes dans la commande.

Il ne peut y avoir qu'une seule ligne de type Remise par commande.
