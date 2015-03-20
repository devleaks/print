# Calcul des coûts des articles spéciaux

Ce document décrit les formules de calcul des coûts des articles spéciaux, 
et tous les paramètres qui fixent ce coût.

## Principe général

Les formules servent à calculer le prix d'un article.

Les formules disposent donc de l'article en question, et des données associées (catégorie, fournisseur, etc.)

Outre l'article concerné, les formules peuvent utiliser

1. Les dimensions de l'oeuvre (longueur et largeur).
1. Des paramètres liés au coût.
1. Des paramètres non liés au coût.

### Paramètres liés aux coûts

Toutes les formules utilisent des variables pour les coûts.

Aucune valeur de coût n'a été placée directement dans les formules.

On peut donc modifier les coûts intervenants dans les formules et faire varier les tarifs.

Toutes les variables liées aux coûts sont des *articles* dans la base de données des articles.
Le nom du paramètre dans la formule est en fait la *référence de l'article*.

Tous les prix sont arrondis à la valeur supérieure.


Si la formule du coût de l'article BeauCadre est la suivante:

	prix = 2 x (longueur + largeur) x Article(PrixDuCadreAuMetre)

et si on souhaite modifier le prix du cadre, il faut modifier le _prix_ de l'article
dont la référence est `PrixDuCadreAuMetre`.

Seul un programmeur sera capable de modifier la "formule de calcul", mais les paramètres utilisés dans la formule peuvent être modifiés en ajustant le coût de l'article correspondant au paramètre.

### Autres paramètres, non liés aux coûts

Les formules utilisent parfois des paramètres non liés au coût des articles.
Par exemple, il existe un paramètre qui fixe la longueur maximale d'un ChromaLuxe.

	longueur < Parametre(LongueurMaximum)

signifie que la longueur d'un article doit être inférieur au paramètre dont le nom est `LongueurMaximum`.

Tous les paramètres sont ajustables dans l'application, dans la gestion de l'application.
Suivre le lien 'Paramètres'.

Tous les paramètres utilisés dans des formules de calcul sont dans le _domaine_ de paramètre `formule`.


### Données par article

Souvent, il faut fournir les données suivantes:

* `largeur`
* `hauteur`

Ces données doivent être fournies en centimètres.

## Coût du ChromaLuxe

### Formule

	surface = largeur * hauteur;
	surface_maximum = Parametre(SublimationMaxHeight) x Parametre(SublimationMaxWidth)
	rapport_surface = surface / surface_maximum
	
	SI surface <= Parametre(SurfaceXS)
		Prix = Article(ChromaXS) x rapport_surface
	SINON SI surface <= Parametre(SurfaceS)
		Prix = Article(ChromaS) x rapport_surface
	SINON SI surface <= Parametre(SurfaceM)
		Prix = Article(ChromaM) x rapport_surface
	SINON SI surface <= Parametre(SurfaceL)
		Prix = Article(ChromaL) x rapport_surface
	SINON SI surface <= surface_maximum
		Prix = Article(ChromaXL) x rapport_surface
	SINON
		ERREUR = Surface supérieure à surface maximum.
		
	SI PRIX < Parametre(Chroma_Min)
	ALORS Prix = Parametre(Chroma_Min)

#### Paramètres

Parametre|Valeur|Explication
---------|------|-----------
SublimationMaxWidth|110 cm|Largeur maximale pour la sublimation
SublimationMaxHeight|170 cm|Hauteur maximale pour la sublimation
SurfaceXS|5000 cm2|Surface Très Petit ChromaLuxe
SurfaceS|7500 cm2|Surface Petit ChromaLuxe
SurfaceM|10000 cm2|Surface Moyen ChromaLuxe
SurfaceL|12500 cm2|Surface Grand ChromaLuxe
SurfaceXL|18700 cm2|Surface Très Grand ChromaLuxe, surface maximale.


#### Prix

Article|Explication
-------|-----------
ChromaXS|Prix ChromaLuxe Très Petit
ChromaS|Prix ChromaLuxe Petit
ChromaM|Prix ChromaLuxe Moyen
ChromaL|Prix ChromaLuxe Grand
ChromaXL|Prix ChromaLuxe Très Grand


Pour changer le prix des ChromaLuxe, vous devez ajuster le prix des articles dont la référence est
reprise dans la colonne Article, ci-dessus.

Ces cinq articles sont dans la catégorie 'ChromaSupport'.



## Coût des renforts

### Formule

	Si l'article dispose d'un cadre et si les dimensions de l'article sont supérieures à Paramètre(RenfortMaxHeight) x Paramètre(RenfortMaxWidth)
		on place un refort gratuitement; Prix = 0

	Prix = 2 x (largeur - Paramètre(Inside) +  hauteur - Paramètre(Inside)) x Prix(Renfort) / 100
	
	SI Prix < Prix(Renfort_Min)
	ALORS Prix = Prix(Renfort_Min)


#### Prix

Article|Explication
-------|-----------
Renfort|Prix du renfort, en € par mètre
Renfort_Min|Prix minimum pour les renforts


#### Paramètres

Paramètre|Explication
-------|-----------
RenfortMaxHeight|Hauteur minimum avant que le renfort soit placé d'office, et gratuitement
RenfortMaxWidth|Largeur minimum avant que le renfort soit placé d'office, et gratuitement
InsideChromaLuxe|Distance, en cm, mesuré à partir du bord, pour le placement des renforts sur les ChromaLuxes
InsideSupport|Distance, en cm, mesuré à partir du bord, pour le placement des renforts sur tous les supports autres que le ChromaLuxe


## Coût des cadres

Depuis une discussion au début du mois de janvier, tous les prix des cadres ont tentativement
été formulés grâce à une formule de régression linéaire simple (voir ci-dessous):

	Longueur = 2 x (largeur +  hauteur)
	Prix = Longueur * A + B

### Prix du montage

	SI (largeur + hauteur) > Parametre(LargeFrame)
		Prix = Article(Montage170L)
	SINON
		Prix = Article(Montage170M)

Le prix du montage est fixé en fonction du périmètre du cadre, et une valeur pivot déterminant quand le cadre est jugé "grand".

## Coût des articles vendu "au périmètre" (à la longeur)

Tous les articles dont le prix est calculé proportionnellement au prérimètre (2 x L + 2 x H), la formule unique utilise une régression linéaire.

Pour un article dont la REFERENCE est X, le prix est calculé avec les deux articles dont les références sont X_A et X_B.
La formule de calcul, générique, est:

	Périmètre = 2 x Largeur + 2 x Hauteur
	Prix = Article(X_A) * Périmètre + Article(X_B)

Intuitivement, les paramètres correspondent à

* X_B: Le coût de mise en route, et aussi coût minimal.
* X_A: Le coût "au mètre" de l'article.

## Coût des articles vendu "à la surface"

Tous les articles dont le prix est calculé proportionnellement à la surface (L x H), la formule unique utilise une régression linéaire.

Pour un article dont la REFERENCE est X, le prix est calculé avec les deux articles dont les références sont X_A et X_B.
La formule de calcul, générique, est:

	Surface = Largeur x Hauteur
	Prix = Article(X_A) * Surface + Article(X_B)

Intuitivement, les paramètres correspondent à

* X_B: Le coût de mise en route, et aussi coût minimal.
* X_A: Le coût "au mètre carré" de l'article.


# Règles Métiers

Les règles suivantes ont été ajoutées.

## Arrondis

### Articles

Les prix des ChromaLuxe et des cadres sont arrondis à l'unité supérieure.

Tous les autres prix sont arrondis à la demi unité la plus proche.


### Calculs intermédiaires, TVA, etc.

Dans tous les calculs intermédiaires (par exemple prix unitaire multiplié par quantité, application de la TVA de 21% sur les articles, etc.)
les montants sont arrondis au centime.


### Ristournes et Suppléments

Les remises et suppléments sont arrondis au centime.


### Factures globales et montants à payer

Le montant final total TVA comprise, et le montant final total hors TVA _dans le cas d'une facture sans TVA_, est arrondi à 5 centimes.
(Note dans le cas d'une facture avec TVA, le montant final total hors TVA est arrondi au centime.)


## Renforts

Le prix des renforts est basé sur le périmètre des renforts placés.

Les renforts sont placés 10cm à l'intérieur des bords d'un ChromaLuxe.

Les renforts sont placés 5cm à l'intérieur des bords de tous les autres supports.

Ceci n'est bien sûr valable que pour le calcul du prix des renforts.
Les renforts peuvent toujours être placés arbitrairement plus à l'intérieur ou à l'extérieur, mais cela n'affectera pas le calcul du coût.

Le prix minimum pour les renforts est celui de l'article Renfort_Min.


## ChromaLuxe

Le prix minimum pour les ChromaLuxe est le prix de l'article ChromaLuxe_Min.


## Prix Minimum

Certains articles possèdent un "prix minimum", de mise en route, pour des petites dimensions.

Le prix minimum d'un article dont la référence est `REF` est déterminé par le `prix_de_vente` de l'article dont la référence est `REF_Min`.
