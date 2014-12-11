# Paramètres

Ce document décrit les paramètres de l'application et leur implication dans l'exécution de l'application.

## Domaines de paramètres

Les paramètres sont organisés par _domaine_.
Un domaine de paramètre correspond à un type de paramètre.

Par exemple, il existe le _domaine_ de paramètre `langue`.
Ce domaine de paramètre regroupe toutes les langues qu'il est possible d'assigner à un client.

Domaine | Utilisation
application|Paramètres utilisés dans l'application
chroma|Type de ChromaLuxe (White glossy, clear mat, etc.)
delay|Délais en jour pour supplément. Non utilisé.
extra|Type d'extra cumulé aux commandes ou lignes de commandes
formule|Paramètre pour une formule.
langue|Langues possible pour l'application
paiement|Type de paiement: Banksys, carte de crédit, virement...
role|Roles dans l'application: Manager, employé...
title|Titre pour les clients: Mr, Mme, SPRL...

## Paramètres de l'application

Paramètre|Utilisation
auto_notify_completion|Si vrai, envoie un message au client lorsque la commande est terminée.
auto_send_bill|Si vrai, place la facture issue d'une commande directement dans l'état Envoyé.
auto_submit_work|Si vrai, soumet immédiatement le travail d'une commande résultant de la conversion d'un devis.


## Paramètres des formules de calcul des coûts des articles

Voir l'aide sur les _Calculs des coûts_.

Il ne faut pas supprimer ces paramètres, sous peine de rendre les calculs des coûts incorrects.
On peut cependant ajuster les valeurs de ces paramètres pour ajuster les formules.

## Paramètres à ne pas changer

Les paramètres dans les domaines suivants ne peuvent pas être modifiés sans modifier le code.

Domaines: Extra, Langue, Role.