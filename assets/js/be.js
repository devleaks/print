var BE = d3.locale ({
  "decimal": ",",
  "thousands": ".",
  "grouping": [3],
  "currency": ["", " €"],
  "dateTime": "%a %b %e %X %Y",
  "date": "%d/%m/%Y",
  "time": "%H:%M:%S",
  "periods": ["AM", "PM"],
  "days": ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
  "shortDays": ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],
  "months": ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
  "shortMonths": ["Janv", "Févr", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]
});

var docTypes = {
	BID: {label: "Offre", color: "Plum"},
	BILL: {label: "Facture", color: "LimeGreen"},
	ORDER: {label: "Commande", color: "LightGreen"},
	TICKET: {label: "VC", color: "Aquamarine"},
	REFUND: {label: "Remb", color: "SandyBrown"},
	CREDIT: {label: "NC", color: "Coral"},
}

var colors = [];
var labels = [];
for (var t in docTypes) {
    if (docTypes.hasOwnProperty(t)) {
		colors.push(docTypes[t]['color']);
		labels.push(docTypes[t]['label']);
    }
}
var docTypesColors = d3.scale.ordinal().domain(labels)
                                    .range(colors);
