var date = new Date(); //Nouvelle date (le jour mï¿½me pas dï¿½faut)

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};


function changeDate () {
	var Newdate = new Date($('#annee').val()+'-12-31'); // LÃ  on initialise une nouvelle variable qui prend cette date : "31/12/'Date sï¿½lï¿½ctionnner par l'utilisateur'"
	var select = $('#mois');

	select.empty();
	
	if (parseInt($('#annee').val()) == date.getFullYear()) { // LÃ  on vÃ©rifie que si c'est la celle qui est actuel alors :
		for(var i = 1; i < date.getMonth() + 2; i++) {

			if(i < 10) {
				select.append($("<option></option>")
					.attr("value", i)
					.text('0' + i));
			} else {
				select.append($("<option></option>")
					.attr("value", i)
					.text(i));
			}


			
		}

		// valeur par défaut pour l'année actuel
		var mois       = getUrlParameter('mois');
		$('#mois option').each(function(){
			
			if(mois != null) {
				
				if($(this).attr("value") == mois) {
					$(this).attr("selected", "selected");
				}
				
			} else {
				
				if($(this).attr("value") == date.getMonth()+1) {
					$(this).attr("selected", "selected");
				}
				
			}

		});

		
	} else { //Sinon
		for(var i = 1; i < 13; i++) {
			if(i < 10){
			 	select.append($("<option></option>")
					.attr("value", i)
					.text('0' + i));
			} else {
				select.append($("<option></option>")
					.attr("value", i)
					.text(i));
			}
		                    
		}
	}
}

$(function () {
    changeDate();

    $('#annee').change(function () {
		changeDate();
	});
});