function ajouter_horsForfait() {
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	let id = document.getElementsByClassName("horsForfaitInputDiv").length + 1;

	var e = document.getElementById("horsForfaitContainer");
	e.innerHTML += render_horsForfait(id);
	
	eHorsForfaitNumber.setAttribute("value", id);
}

function retirer_horsForfait() {
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	let e = document.getElementsByClassName("horsForfaitInputDiv");
	
	if(e.length-1 > 0)
		e[e.length-1].outerHTML = "";
	
	eHorsForfaitNumber.setAttribute("value", "" + e.length);
}

function render_horsForfait(num) {
	return '<div class="horsForfaitInputDiv"><label for="horsForfait' + num + 'Libelle">Libell&#233; :</label><input type="text" name="horsForfait' + num + 'Libelle"/><label for="horsForfait' + num + 'Montant"> Montant :</label><input type="number" min="0" value="0" name="horsForfait' + num + 'Montant"/></div>';
}

$(function () {
	ajouter_horsForfait();
});