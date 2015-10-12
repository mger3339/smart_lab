// super admin clients functionality
$(document).ready(function() {
	
	$('.clients').on('focusout', '.client-name-input', function() {
		if ($(this).val() && !$('.client-slug-input').val()) {
			var slug = $(this).val().toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
			$('.client-slug-input').val(slug);
		}
	});
	
});

