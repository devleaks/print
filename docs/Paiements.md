# Gestion des Paiements

## Introduction

### Ventes

Chaque devis, commande, ou ticket de caisse donne lieu à une vente, qu'elle soit nominative (client précisé) ou anonyme (client au comptoir).

A chaque vente sont associés un ou plusieurs paiements, jusqu'au moment où les paiements couvent le montant total de la vente.
Tant que le montant des paiements est inférieur au montant de la vente, la vente est en état <span class="label label-info">A payer</span>.
Quand le montant des paiements atteint le montant de la vente,
le statut de la commande prend celui des travaux associés (si il y en a), ou
la vente est <span class="label label-info">Clôturée</span> puisque le paiement d'une vente est la dernière opération.


## Entrée des Paiements

Un paiement peut être enregistré en regard d'un achat à payer.

Si une commande, une facture, un ticket de caisse est déjà payé,
il n'est plus possible d'ajouter un paiement.

L'origine du paiement n'est pas retenue, sauf dans le cas où un client paie les factures d'un autre client.


Les paiements sont entrés en regard des ventes à deux endroits:

  1. Dans la vente elle-même, choisir l'option `Paiements`.
  1. Pour les factures, il est possible d'effectuer un seul paiement pour plusieurs factures.

Dans le premier cas, si le montant entré est supérieur au montant à payer, le solde apparaît comme crédit (pour le client),
et est utilisable pour payer d'autres factures.

Dans le deuxième cas, choisir l'écran des factures impayées du client, cocher les factures pour lesquelles le client paie,
et choisir l'option `Ajouter un paiement`.
Le montant indiqué est ventilé entre les factures cochées, par date de création des factures (les plus anciennes factures sont payées en premier lieu).

Si les factures sélectionnées émanent de plusieurs clients différents, il faut préciser quel est le client qui paie pour toutes les factures.

Dans tous les cas:
* Si le montant ajouté est suffisant, toutes les factures passeront à l'état du paiement reçu.
* Si le montant est insuffisant, certaines factures resteront dans l'état `A payer`.
* Si le montant est supérieur au montant total des factures cochées, l'excédant est laissé comme _disponible_.
L'excédant disponible apparaît alors dans l'écran de paiement d'une vente, et peut être affecté à cette vente en choisissant le mode de paiement `Utilisation de crédits`.

### Apport d'argent sans vente

Il est possible d'ajouter un crédit à un client sans l'attacher à une vente.
Ce crédit sera ensuite consommé en payant des factures par `Utilisation des crédits`.

Attention: Tant que les crédits n'ont pas été consommés par les ventes,
apporter un crédit fausse temporairement le bilan du client.
En effet, le bilan du client reflètera le crédit apporté, mais les ventes effectuées par ce client
resteront impayées et dûes jusqu'au moment de leur paiement, via ce crédit ou par un autre moyen.
Pour ne pas arriver dans ce cas, il suffit tout simplement d'utiliser les opérations normales décrites
dans la section ci-dessus.

Pour ajouter un crédit à un client, dans la comptabilité, choisir l'option `Ajouter un paiement sans vente`.
Choisir le client, indiquer le montant et l'orgine du payment (virement, etc.).
Il est vivement conseillé d'également rentrer un commentaire pour se rappeler de la raison de ce paiement sans facture en regard.

Les crédits déposés pour un client ne peuvent être consommé que sur des ventes assignées à ce client.
Un client ne peut pas payer la facture d'un autre client avec ses crédits.

Attention: Ce mode de fonctionnement a été ajouté pour faire face à des situations exceptionnelles.
Il n'est pas normal de recevoir un paiement pour une commande qui n'est pas dans le système.
Avant de créditer le client, il est important de se poser la question de pourquoi il n'y a pas des commandes
en regard de ce paiement.

Dans l'application, l'entrée des paiements en regard des commandes n'a pour seul but que
de s'assurer que le montant dû est entré dans la comptabilité.

Entrer les paiements en regard des commandes est une opération importante qui garantit non seulement le suivi de la commande
mais aussi le suivi global du client.


## Suppression des paiements

La suppression des paiements doit se faire de la même manière que pour l'entrée des paiements.

En cas d'erreur, on est obligé d'utiliser la même méthode d'annulation que la méthode de création.

Si on a erronément introduit un paiement en l'ajoutant à une commande individuelle,
on peut supprimer ce paiement en allant sur la gestion de la commande, et en supprimant le paiement.
La commande reviendra dans l'état <span class="label label-info">A payer</span> si le montant
des paiements ne couvre plus le montant à payer.

Si on a erronément introduit un paiement ventilé entre plusieurs factures, il faut annuler ce paiement
et toutes les facturées couvertes par le paiement seront débitées du montant crédité.
Il n'est pas possible d'annuler le paiement d'une seule facture, car le montant réparti entre les factures
dépend précisément du montant global crédité.

### Cas Particuliers

#### Paiements par crédits

Si on annule un paiement effectué par le transfert d'un crédit dont le client disposait,
l'annulation du paiement résultera en l'annulation du paiement proprement dit, et la restitution du crédit.

(Note: Si le crédit utilisé pour le paiement résultait de l'addition de plusieurs crédits disponibles pour le client,
l'annulation du paiement provoquera la restitution d'une seul crédit reprenant le montant total de crédit utilisé.)

#### Paiement via un Remboursement ou une Note de Crédit

Le montant d'un remboursement ou d'une note de crédit non remboursé au client apparaîtra dans la liste des crédits disponibles.

Si ce crédit est utilisé, il sera 1. d'abord transformé en ligne de crédit et sera 2. ensuite débité d'un achat à payer.
Il s'agit là de deux opérations:

	1. la première marque le remboursement ou la note de crédit comme utilisé et crée une ligne de crédit disponible pour les achats.
	1. la seconde opération consomme ce crédit (partiellement ou totalement) pour payer un achat.

Si on souhaite complètement annuler cette opération, il faut donc le faire en deux étapes:

	1. Annuler le paiement de la vente en supprimant le paiement via les crédits disponibles.
	1. Annuler la "transformation" du remboursement ou de la note de crédit en ligne de crédit.

Pour la première étape, visualiser les paiements de l'achat et supprimer le paiement effectué avec le crédit.
Ceci restituera un ligne de crédit à nouveau disponible pour d'autres achats.
On peut laisser cette ligne de crédit telle quelle.

Si on souhaite annuler la transformation du remboursement ou la note de crédit en ligne de crédit,
il faut visualiser les "paiements" de la note de crédit, et supprimer la ligne de crédit.

(Note: Il n'est parfois plus possible de faire cette dernière opération,
si les crédits ont été factionnés sur plusieurs commandes.)

#### Apport d'argent sans vente

En cas de suppression d'un apport d'argent (crédit) à un client sans vente associée,
le crédit sera supprimé de toutes les commandes ayant utilisé partiellement ou totalement ce crédit.

#### Paiements cash (à la caisse)

L'annulation d'un paiement cash (à la caisse) provoquera le retrait du paiement de la caisse le jour du paiement.

En conséquence, tous les montants de la caisse seront invalides à partir du moment où le paiement a été supprimé.

Il faut, dans ce cas, recréer, par exemple, tous les totaux quotidiens depuis le jour de l'annulation.

## Manipulations de la caisse

Les manipulation de la caisse non liées à des achats peuvent être modifiées à tout moment.

Aller dans l'écran `Caisse - Manipulation directes`.
Toutes les entrées et sorties de la caisse sont visibles et peuvent être annulées, ou modifiées.

Les entrées de la caisse liées à des achats ne peuvent pas être modifiées ici.
Il faut modifier de tels paiements via l'achat et annuler le paiement via la caisse.

Attention, toute modification du montant d'un mouvement de la caisse invalide tous les montants entrés après ce mouvement.
