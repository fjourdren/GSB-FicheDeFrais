function ajouter_liste_horsForfait() {
	
	if(map_horsForfait.length > 0) {
		for(var i = 0; i < map_horsForfait.length; i++) {
			$("#horsForfaitContainer").append(render_old_horsForfait(i, map_horsForfait[i]['libFraisHF'], map_horsForfait[i]['dteFraisHF'], map_horsForfait[i]['quantite'], map_horsForfait[i]['montant']));
		}
	} else {
		$("#horsForfaitContainer").append(render_new_horsForfait(0));
	}

	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", document.getElementsByClassName("horsForfaitInputDiv").length);
}

function ajouter_horsForfait() {
	let e = document.getElementsByClassName("horsForfaitInputDiv");
    $("#horsForfaitContainer").append(render_new_horsForfait(e.length));
	
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", e.length);
}

function retirer_horsForfait() {
	let e = document.getElementsByClassName("horsForfaitInputDiv");

	if(e.length - 1 > 0)
		$("#horsForfaitContainer .horsForfaitInputDiv:last-child").remove();
	
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", e.length);
}

function render_new_horsForfait(num) {
	return '<div class="horsForfaitInputDiv"><label for="horsForfait' + num + 'Libelle">Libell&#233;* : </label><input type="text" name="horsForfait' + num + 'Libelle"/><label for="horsForfait' + num + 'Quantite"> Quantit&#233;* : </label><input type="number" min="1" value="1" name="horsForfait' + num + 'Quantite"/><label for="horsForfait' + num + 'Montant"> Montant* : </label><input type="number" min="0" value="0" name="horsForfait' + num + 'Montant"/><label for="horsForfait' + num + 'Date"> Date* : </label><input type="date" value="' + getDate() + '" name="horsForfait' + num + 'Date"/></div>';
}

function render_old_horsForfait(num, libelle, date, quantite, montant) {
	return '<div class="horsForfaitInputDiv"><label for="horsForfait' + num + 'Libelle">Libell&#233;* : </label><input type="text" value="'+ libelle +'" name="horsForfait' + num + 'Libelle"/><label for="horsForfait' + num + 'Quantite"> Quantit&#233;* : </label><input type="number" min="1" value=' + quantite + ' name="horsForfait' + num + 'Quantite"/><label for="horsForfait' + num + 'Montant"> Montant* : </label><input type="number" min="0" value=' + montant + ' name="horsForfait' + num + 'Montant"/><label for="horsForfait' + num + 'Date"> Date* : </label><input type="date" value="' + date + '" name="horsForfait' + num + 'Date"/></div>';
}

$(function () {
	ajouter_liste_horsForfait();
});