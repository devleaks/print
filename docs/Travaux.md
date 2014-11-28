# Gestion des Travaux et des Tâches

Le coeur de l'application est la gestion des travaux à exécuter pour satisfaire les commandes.

Seules les commandes peuvent engendrer des travaux.
Les devis et les factures n'engendrent aucun travail.

Chaque commande peut donner lieu à un travail.
Un travail est constitué de une ou plusieurs tâches à accomplir.

Les tâches engendrées par une commande dépendent des articles contenus dans la commande.

A chaque article, on peut assigner une ou plusieurs tâches à accomplir.


Si une commande contient des articles qui ont des tâches associées,
lorsqu'on soumettra la commande pour sa réalisation,
la commande engendrera un travail constitué de toutes les tâches liées aux articles dans la commande.


La majorité des articles n'ont cependant aucune tâche associée.
Une commande qui ne contient que des articles sans tâche associée ne génère donc aucun travail.


## Tâches Associées Aux Articles

Avant d'associer des tâches aux articles, il faut définir ces tâches.


### Tâches

Dans l'application, rendez-vous dans la Gestion du magasin.
Choisir l'option "Tâches".

L'écran suivant présente les tâches déjà présentes dans le système.
On peut ajouter de nouvelles tâches si nécessaire.

On trouve par exemple, les tâches de Découpe, Sublimation, ou Emballage.

(Note: Il est possible d'associer un coût horaire et un coût financier à chacune de ces tâches.
Ces informations seront précisées plus tard, pour raffiner la gestion du travail.)

### Associer les tâches aux articles.

Pour associer les tâches aux articles, rendez-vous dans la Gestion du magasin.
Choisir l'option "Articles".

Dans la gestion d'article, il est possible de gérer tous les articles, en créer de nouveaux, en supprimer d'anciens,
ou changer des valeurs associées aux articles, comme par exemple le prix ou le taux de TVA.

Pour associer des tâches aux articles, il faut choisir un article, et en voir ces détails.
Pour ce faire, on peut sélectionner un article en entrant sa référence, ou son nom dans l'entête des colonnes.

Cherchons par example ChromaLuxe dans la case du libelle long.

Tous les articles dans le libelle contient ChromaLuxe apparaissent.

Cliquer sur l'ocîne de l'oeil pour " voir " l'article ChromaLuxe.

La page suivante énumère toutes les informations associées à l'article ChromaLuxe: Prix, founisseur, etc.

Sous le tableau qui reprend ces informations, il y a une section "Tâches Associées".

Choisir un tâche, par exemple Emballage, et préciser si on le souhaite un numéro de position.
La tâche d'emballage arrivant à la fin, on va lui donner la position 9999.
On peut également ajouter un commentaire.

Presser Ajouter une tâche.

La tâche d'Emballage est maintenant associée à l'article ChromaLuxe.

(Note: On ne peut associer une tâche qu'une seule fois à un article.
La tâche Emballage ne peut donc plus être associée une deuxième fois à l'article ChromaLuxe.)


Dès lors, lorsque une commande contiendra l'article ChromaLuxe,
lorsqu'on soumettra le travail à réaliser pour cette commande,
la travail de cette commande contiendra la tâche à accomplir "Emballage".

## Soumission d'une commande

Lorsqu'une commande est complète, lorsque tous les articles ont été ajouté à une commande,
on peut la soumettre pour sa réalisation.

Cela revient à "confirmer" le travail à exécuter.

Lorsque la commande est prête à être réalisée, choisir l'action "Soumettre le travail".

Si la commande ne contient aucun article qui a des tâches associées, la commande n'engendrera aucun travail pour sa réalisation.
En conséquence, la commande sera immédiatement satisfaite, puisqu'il n'y a aucune tâche à accomplir.

Si la commande contient au moins un article qui a une tâche associée, la commande engendrera un travail.
Ce travail sera composé de une ou pluseurs tâches à accomplir.

Lorsqu'elle est "soumise pour travaux", une commande engendre un travail, composé de une ou plusieurs tâches.


## Travail d'une commande

Il existe un travail par commande.

Chaque travail est composé de une ou plusieurs tâches.

La date de livraison de la commande est répercutée dans le travail.
Le travail doit être réalisé avant la date de livraison de la commande pour être réalisé dans les temps.


### Liste des tâches

Chaque tâche dans ce travail résulte de la présence d'un article dans une ligne de la commande qui l'a engendrée.
Puisque chaque ligne de commande peut avoir une date de livraison particulière, la date d'échéance de réalisation
des tâches est déterminée par la date de livraison de la ligne de commande correspondante.


## Execution du travail

Le travail d'une commande est exécuté en exécutant chacune des tâches qui le compose.


### Execution des tâches

Chaque tâche peut être exécutée en une ou deux étapes.

En une seule étape, on confirme simplement que la tâche est terminée.

En deux étape, dans un premier temps on prend la tâche en charge.
Cela signifie qu'on va l'exécuter.
Cela indique aussi aux autres collaborateurs qu'on va s'occuper de cette tâche et qu'il n'est pas nécessaire
que quelqu'un d'autre s'en charge.
Dans un deuxième temps, on va terminer la tâche, pour indiquer qu'elle est finie.


### Exceptions

Il existe deux procédures d'exception:

* Avertissement
* Refiare la tâche


#### Avertissement

A tout moment on peut placer la tâche dans le mode d'avertissement.
Dans ce cas, le travail qui contient cette tâche passera en mode d'avertissement, et en conséquence,
la commande qui a engendré ce travail passera en mode d'avertissement aussi.

En inspectant la liste des commandes, on repèrera immédiatement la commande qui est dans un état d'avertissement.


#### Refaire une tâche

Il est possible, à tout moment, de "refaire" une tâche.
Cela signifie que toutes les opérations nécessaires pour l'accomplissement de la tâche doivent être recommencées.

Ici encore, cette opération sera répercutée dans l'état du travail, et de la commande.


## Evolution du travail

Au fur et à mesure de la prise en charge des tâches et de leur exécution,
le travail d'une commande évoluera.

Lorsque toutes les tâches sont terminées, le travail est terminé.


## Réalisation de la commande

Lorsque le travail associé à une commande est terminé, la commande est réalisée.

Si l'option est activée, un message (email) est automatiquement envoyé au client
pour lui indiquer que sa commande est prête.

Enfin, si l'option est activée, la commande est clôturée et transformée en facture.

