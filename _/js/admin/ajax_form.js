// AJAX form functionality
$(document).ready(function() {
	
	$('.admin-content').on('submit', '.ajax-form', function(event) {
		
		var confirmSubmitMsg = $(this).attr('data-confirm');
		var confirmSubmit = (confirmSubmitMsg) ? confirm(confirmSubmitMsg) : true;
		
		if (confirmSubmit) {
			
			var wrapper = $(this).parents('.ajax-form-wrapper');
			var requestURL = $(this).attr('action');
			
			if (requestURL) {
				
				var requestOptions = {
					type: 'POST',
					data: $(this).serialize(),
					success: function(data) {
						if (data.content) {
							$(wrapper).replaceWith(data.content);
						}
					}
				};
				
				ajaxRequest(requestURL, requestOptions);
				
				$(this).find('button').attr('disabled', 'disabled');
			}
		}
		
		event.preventDefault();
	});
	
});

