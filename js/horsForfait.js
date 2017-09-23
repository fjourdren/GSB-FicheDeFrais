function ajouter_liste_horsForfait() {
	
	if(map_horsForfait.length > 0) {
		for(var i = 0; i < map_horsForfait.length; i++) {
			$("#horsForfaitContainer").append(render_old_horsForfait(i, map_horsForfait[i]['libFraisHF'], map_horsForfait[i]['dteFraisHF'], map_horsForfait[i]['quantite'], map_horsForfait[i]['montant']));
		}
	} else {
		ajouter_horsForfait();
	}

	let e = document.getElementsByClassName("horsForfaitInputDiv");
	$("#horsForfaitNumber").attr("value", e.length);
}

function ajouter_horsForfait() {
	let e = document.getElementsByClassName("horsForfaitInputDiv");
    $("#horsForfaitContainer").append(render_horsForfait(e.length));

	if($("#horsForfaitContainer .horsForfaitInputDiv").length > 1) {
		$(".removeHorsForfaitButton").show();
	} else {
		$(".removeHorsForfaitButton").hide();
	}
	
	$("#horsForfaitNumber").attr("value", e.length);
}

function retirer_horsForfait() {
	let e = document.getElementsByClassName("horsForfaitInputDiv");

	if(e.length - 1 > 0) {
		$("#horsForfaitContainer .horsForfaitInputDiv:last-child").remove();

		//apparition et disparition des boutons de supression des hors forfaits
		if($("#horsForfaitContainer .horsForfaitInputDiv").length > 1) {
			$(".removeHorsForfaitButton").show();
		} else {
			$(".removeHorsForfaitButton").hide();
		}
	}
	
	$("#horsForfaitNumber").attr("value", e.length);
}

function retirer_horsForfait_id(id) {
	
	if(!confirm('Voulez-vous vraiment supprimer ce hors forfait ?')) {
		return false;
	}

	let e = $(".horsForfaitInputDiv");

	//apparition et disparition des boutons de supression des hors forfaits
	if(e.length - 1 > 0) {
		$("#horsForfaitContainer .horsForfaitInputDiv").get(id).remove();

		if(e.length == 1) {
			$(".removeHorsForfaitButton").hide();
		}
	}

	//recalcul et modification avec des nouveaux id
	let elementsHorsForfaitInputs = $(".horsForfaitInputDiv");
	for(let i = 0; i < elementsHorsForfaitInputs.length; i++) {
		let element = elementsHorsForfaitInputs.get(i);

		let idHorsForfait = element.getAttribute("idHorsForfait");

		//mise à jour des inputs avec le nouvel id
		if(idHorsForfait != i) {
			$("input[name='horsForfait" + idHorsForfait + "Libelle']").attr('name',  'horsForfait' + i + 'Libelle');
			$("input[name='horsForfait" + idHorsForfait + "Quantite']").attr('name',  'horsForfait' + i + 'Quantite');
			$("input[name='horsForfait" + idHorsForfait + "Montant']").attr('name',  'horsForfait' + i + 'Montant');
			$("input[name='horsForfait" + idHorsForfait + "Date']").attr('name',  'horsForfait' + i + 'Date');
		}
	}
	
	$("#horsForfaitNumber").attr("value", e.length - 1);
}

function render_horsForfait(num) {
	return '<div class="horsForfaitInputDiv" idHorsForfait="' + num + '"><label for="libelle">Libell&#233;* : </label><input type="text" value="Sans nom" name="horsForfait' + num + 'Libelle"/><label for="quantite"> Quantit&#233;* : </label><input type="number" min="1" value="1" name="horsForfait' + num + 'Quantite"/><label for="montant"> Montant* : </label><input type="number" min="0" value="0" name="horsForfait' + num + 'Montant"/><label for="date"> Date* : </label><input type="date" value="' + getDate() + '" name="horsForfait' + num + 'Date"/><img class="iconeMin removeHorsForfaitButton" src="images/icones/delete.png" onclick="retirer_horsForfait_id('+ num +');" alt="supprimer"/></div>';
}

function render_old_horsForfait(num, libelle, date, quantite, montant) {
	return '<div class="horsForfaitInputDiv" idHorsForfait="' + num + '"><label for="libelle">Libell&#233;* : </label><input type="text" value="'+ libelle +'" name="horsForfait' + num + 'Libelle"/><label for="quantite"> Quantit&#233;* : </label><input type="number" min="1" value=' + quantite + ' name="horsForfait' + num + 'Quantite"/><label for="montant"> Montant* : </label><input type="number" min="0" value=' + montant + ' name="horsForfait' + num + 'Montant"/><label for="date"> Date* : </label><input type="date" value="' + date + '" name="horsForfait' + num + 'Date"/><img class="iconeMin removeHorsForfaitButton" src="images/icones/delete.png" onclick="retirer_horsForfait_id('+ num +');" alt="supprimer"/></div>';
}