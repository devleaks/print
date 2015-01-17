## prints

This folder contains Yii2 views dedicated to printing and PDF generation.

PHP scripts in these folders are regular Yii2 views, and behave as such.
They are just conveniently grouped into a common directory, distinct from "screen" or "web" views.

Scripts are most of the time called by renderPartial, and/or render.

A companion widget app\widgets\GridViewPDF (and app\widgets\DataColumnPDF) are stripped down version of GridView and DataColumn widgets for PDF generation and printing, in both console and web modes.



### Account

Comptes clients: Extraits de compte.

### Common

Header, footer

### Cover Letter

Lettre de garde (structurée)


#### Structure





### Document

Devis, commande, facture. Format A4.

Note de crédit (à faire?)

### Ticket

Autre nom pour Document, mais en format A5.


### Label

Etiquettes à coller au dos des travaux ou coupes.

Il y a aussi une étiquette pour les commandes, qui reprend toutes les lignes de commande.

Il y aura une étiquette d' "authenticité" collée au dos des ChromaLuxe, avec QR Code.
