// general forms functionality
$(document).ready(function() {
	
	$('#wrap').on('change', 'input[type=checkbox]', function() {
		$(this).siblings('.checkbox-label').toggleClass('checked');
	});
	
});
