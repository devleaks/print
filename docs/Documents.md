# Gestion des Commandes

L'application permet de gérer des "commandes", depuis leur estimation, jusqu'à la facturation.

Cette gestion se fait via trois types de documents: Les devis, les commandes, et les factures.

Deux autres types de document existent à côté de ces types de document principaux: Les bons de livraison et les notes de crédit.

A partir de l'application, il est possible de créer un devis, une commande, une facture, ou une note de crédit.
Les bons de livraisons sont créés à partir de commandes ou factures.

# Devis, commandes et factures

Ces trois types de document sont presque identiques. Ils ne diffèrent que par leur rôle, et les actions qu'ils permettent dans l'application.

## Tronc commun

Ces trois types de document ont en commun la façon dont ils sont composés.

On créer un devis, une commande, ou une facture de la même façon, en choisissant Nouveau devis, nouvelle commande, ou nouvelle facture.

On peut aussi créer

* Une commande sur la base d'un devis existant
* Une facture sur la base d'une commande

Dans ces cas, l'application maintient un lien entre les documents respectifs.


Pour être valable, ces documents doivent obligatoirement avoir

* Un client
* Une date de livraison
* Au moins un article

Lorsqu'un document a été créé, il est possible d'y ajouter des articles ou "lignes de commandes".

La base de données des articles contient tous les articles commercialisés par le magasin.

Les articles suivants ont des dispositions particulières qui seront détaillées ci-dessous:

* ChromaLuxe
* Impression Fine Art
* Article divers
* Remise
* Note de crédit

Tous les autres articles se traitent de la même façon.

Pour les ajouter au devis, à la commande, ou à la facture, entrer quelques caractères de la référence ou du libellé et choisir l'article dans la liste déroulante.

Il est obligatoire de préciser la quantité.

Pour chaque article, ou ligne de commande, il est possible de préciser les choses suivantes:

* Une réduction, exprimée soit par un montant direct, soit par un montant en pourcentage du prix de l'article,
* Un coût supplémentaire, exprimé soit par un montant direct, soit par un montant en pourcentage du prix de l'article,
* Une date de livraison différente de la date de livraison de la commande.

Lorsqu'un article est dans la commande, il est possible de modifier la ligne de commande, ou de la supprimer.

Lorsqu'on modifie une ligne de commande, il est possible de modifier la quantité, la remise ou le supplément, et la date de livraison.
Pour changer le type d'article, il faut supprimer la ligne de commande et en recréer une autre.

Tous les documents peuvent toujours être

* Vus à l'écran,
* Imprimés,
* Envoyés par email.


## Devis

Les devis diffèrent des autres documents par les éléments suivants:

* Ils ont un numéro de référence contenant le mot DEVIS (par exemple YYYY-DEVIS-1XXXX).

Lorsqu'ils sont en cours de réalisation, les devis peuvent:

* Etre modifié en ajoutant ou supprimant des lignes de commande,
* Etre converti en commande par la fonction "Commander",
* Etre annulés, par la fonction "Annuler".

Lorsqu'un devis a été transformé en commande, il ne peut plus être modifié.

Lorsqu'un devis est transformé en commande, la commande peut générer immédiatement le travail nécessaire à la réalisation de la commande.
Cela depénd du paramètre `auto_submit_work`. Si la valeur de ce paramètre est vraie, la travail est automatiquement généré lorsque le devis est converti en commande. Sinon, il faudra manuellement soumettre le travail à partir de la commande (voir ci-dessous).

Le devis passe par les états suivants:

* Ouvert: Le devis peut être modifié, des lignes de commande peuvent être ajoutées ou supprimées. Le devis peut être converti en commande.
* Clôturé: Le devis a été converti en commande. Dans l'interface de l'applcation, il suffit de cliquer sur la pastille présentant l'état du devis (Commandé) pour afficher la commande.
* Annulé: Le devis a été annulé.

Note: Lorsqu'un devis est converti en commande, la commande reçoit un numéro de référence qui est différent du numéro du devis.




## Commande

Les commandes ont un numéro de commande séquentiel sous la forme:

YYYY-1XXXX

où YYYY est l'année de création de la commande,
et 1XXXX est un numéro séquentiel commançant à 10000 lorsque l'année en cours a été clôturée.

Les opérations suivantes peuvent être reéalisées sur les Commandes:

* Etre modifiés en ajoutant ou supprimant des lignes de commande,
* Le travail nécessaire pour réaliser la commande peut être crée par la fonction "Soumettre le travail",
* Etre facturée par la fonction "Facturer".

Lorsqu'une commande a été soumise pour réalisation du travail, elle ne peut plus être modifiée.

Lorsqu'une commande a été convertie en Facture, elle ne peut plus être modifiée.

Lorsqu'une commande est transformé en facture, la facture peut être immédiatement envoyée.
Cela depénd du paramètre `auto_send_bill`. Si la valeur de ce paramètre est vraie, la facture est automatiquement envoyée lorsque la commande est convertie en facture. Sinon, il faudra manuellement envoyer la facture à partir de son affichage (voir ci-dessous).


Les commandes passent par les états suivants:

* Ouverte: On peut modifier la commande, ajouter ou supprimer des lignes de commande.
* A faire: Le travail pour la réalisation de la commande a été soumis et aucune tâche n'a été commencée.
* Réalisation: Le travail pour la réalisation est en cours de réalisation, c'est à dire que une tâche au moins a été commencée.
* Terminée: Le travail pour la réalisation de la commande est terminé; toutes les tâches sont terminées.
* Avertissement: Le travail pour la réalisation de la commande est dans l'état d'Avertissement; une des tâches nécessaire pour la réalisation de la commande est en état d'Avertissement.
* Clôturée: La commande a été traitée, et la facture a été créée.



## Facture

Les factures portent un numéro séquentiel identique aux commandes.

La facture issue d'une commande porte le même numéro que la commande.

Les opérations suivantes peuvent être réalisées sur les factures:

* Envoyer: La facture est envoyée au client, par email, ou par courrier postal.
* Payée: La facture a été totalement payée.

Les factures passent par les états suivants:

* Ouverte: La facture été créée. On peut la modifier en ajoutant ou supprimant des lignes de commande.
* Envoyée: La facture a été envoyée au client.
* Clôturée: La facture est clôturée, et le paiement a été reçu.


## A propos des documents

### Notes de crédit

Les notes de crédit sont des documents un petit peu particulier.
Ils ne devraient être composé que de lignes de commande "Note de crédit".

#### Ligne de commande " note de crédit "

La ligne de commande "Note de crédit" est une ligne de commande particulière, destinée a préciser u nmontant remboursé à un client.

Pour entrer le montant, choisir le type d'_Extra_ "Remise en €", et préciser le montant remboursé dans la case adjacente.

Une autre façon de préciser un crédit, et de choisir un article, et d'offrir une remise de 200% dessus.



### Etat des documents "Créé" 

Un document peut être dans l'état "Créé". Cela signifie que le document a été créé, mais il ne contient aucune ligne de commande.

Pour les documents qui sont dans cet état, il n'est possible que

* de rajouter au moins une ligne de commande, dans ce cas, le document passera dans l'état "Ouverte",
* de l'annuler, dans ce cas, le document passera dans l'état Annulé.



