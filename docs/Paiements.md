# Gestion des Paiements

## Introduction

### Ventes

Chaque devis, commande, ou ticket de caisse donne lieu à une vente, qu'elle soit nominative (client précisé) ou anonyme (client au comptoir).

A chaque vente sont associés un ou plusieurs paiements, jusqu'au moment où les paiements couvent le montant total de la vente.
Tant que le montant des paiements est inférieur au montant de la vente, la vente est en état <span class="label label-info">A payer</span>.
Quand le montant des paiements atteint le montant de la vente,
la vente est <span class="label label-info">Clôturée</span> puisque le paiement d'une vente est la dernière opération.


### Entrée des Paiements

Les paiements sont entrés en regard des ventes à deux endroits:

  1. Dans la vente elle-même, choisir l'option `Paiement`.
  1. Lorsqu'un client fait un paiement pour plusieurs ventes groupées, il faut que ces ventes soient FACTUREES.

Dans le deuxième cas, choisir l'écran des factures impayées du client, cocher les factures pour lesquelles le client paie, et choisir l'option `Ajouter un paiement`.
Le montant indiqué est ventilé entre les factures cochées, par date de création des factures (les plus anciennes factures sont payées en premier lieu).

* Si le montant ajouté est suffisant, toutes les factures passeront à l'état du paiement reçu.
* Si le montant est insuffisant, certaines factures resteront dans l'état `A payer`.
* Si le montant est supérieur au montant total des factures cochées, l'excédant est laissé comme _disponible_.
L'excédant disponible apparaît alors dans l'écran de paiement d'une vente, et peut être affecté à cette vente en choisissant le mode de paiement `Crédit`.

Si un montant n'est pas affecté à une vente, il reste <span class="label label-info">Ouvert</span>.