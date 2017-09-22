function ajouter_horsForfait() {
	let id = document.getElementsByClassName("horsForfaitInputDiv").length + 1;
    $("#horsForfaitContainer").append(render_horsForfait(id));
	
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", id);
}

function retirer_horsForfait() {
	let e = document.getElementsByClassName("horsForfaitInputDiv");

	if(e.length - 1 > 0)
		$("#horsForfaitContainer .horsForfaitInputDiv:last-child").remove();
	
	let eHorsForfaitNumber = document.getElementById("horsForfaitNumber");
	eHorsForfaitNumber.setAttribute("value", "" + e.length);
}

function render_horsForfait(num) {
	return '<div class="horsForfaitInputDiv"><label for="horsForfait' + num + 'Libelle">Libell&#233; : </label><input type="text" name="horsForfait' + num + 'Libelle"/><label for="horsForfait' + num + 'Quantite"> Quantit&#233; : </label><input type="number" min="1" value="1" name="horsForfait' + num + 'Quantite"/><label for="horsForfait' + num + 'Montant"> Montant : </label><input type="number" min="0" value="0" name="horsForfait' + num + 'Montant"/></div>';
}

$(function () {
	ajouter_horsForfait();
});