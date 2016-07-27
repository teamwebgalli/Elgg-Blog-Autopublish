define(['require', 'jquery', 'elgg'], function(require, $, elgg) {
	$(document).ready(function(){
		var statusField = $('#blog_status');
		var publishOnDiv = $('.publish_on');
		var val = statusField.val();
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

