$(function () {
	$('.flashCloseButton').click(function() {
		
		var flash = $(this).parent().closest('div.flash');

		flash.animate({
			opacity: 0,
		}, 600, function() {
		 	flash.remove();
		});
		
	});
});