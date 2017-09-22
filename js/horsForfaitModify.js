function ajouter_liste_horsForfait() {
	
	for(var i = 1; i < map_horsForfait.length; i++) {
	    $("#horsForfaitContainer").append(render_horsForfait(i, map_horsForfait[i]['libFraisHF'], map_horsForfait[i]['quantite'], map_horsForfait[i]['montant']));
	}

	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", document.getElementsByClassName("horsForfaitInputDiv").length);
}

function ajouter_horsForfait() {
	let id = document.getElementsByClassName("horsForfaitInputDiv").length + 1;
    $("#horsForfaitContainer").append(render_horsForfait(id));
	
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", "" + id);
}

function retirer_horsForfait() {
	let e = document.getElementsByClassName("horsForfaitInputDiv");

	if(e.length - 1 > 0)
		$("#horsForfaitContainer .horsForfaitInputDiv:last-child").remove();
	
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", "" + e.length);
}

function render_horsForfait(num, libelle, quantite, montant) {
	return '<div class="horsForfaitInputDiv"><label for="horsForfait' + num + 'Libelle">Libell&#233; : </label><input type="text" value='+libelle+' name="horsForfait' + num + 'Libelle"/><label for="horsForfait' + num + 'Quantite"> Quantit&#233; : </label><input type="number" min="1" value="1" value='+quantite+' name="horsForfait' + num + 'Quantite"/><label for="horsForfait' + num + 'Montant"> Montant : </label><input type="number" min="0" value="0" value='+montant+' name="horsForfait' + num + 'Montant"/></div>';
}

$(function () {
	ajouter_liste_horsForfait();
});