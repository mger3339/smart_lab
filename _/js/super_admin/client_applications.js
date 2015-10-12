// super admin client applications functionality
$(document).ready(function() {
	
	$('.client-applications').on('change', '.client-application-input', function() {
		var applicationName = $('option:selected', this).text();
		$('.client-application-name-input').val(applicationName);
	});
	
});

