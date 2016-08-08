define(['require', 'jquery', 'elgg'], function(require, $, elgg) {
	$(document).ready(function(){
		var statusField = $('#blog_status');
		var val = statusField.val();
		var publishOnDiv = $('.publish_on');
		if( val == 'draft' ){
			publishOnDiv.show();
		} 
		statusField.on('change', function (e){
			var newval = $(this).val();
			if(newval == 'draft'){
				publishOnDiv.show();
			} else {
				publishOnDiv.hide();
			}
		});	
		
	});
});

