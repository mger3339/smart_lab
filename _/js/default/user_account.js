// user account functionality
$(document).ready(function() {
	
	$('.user-account-content').on('submit', '.user-account-form', function(event) {
		var that = $(this);
		var requestURL = $(this).attr('action');
		
		if (requestURL) {
			
			var requestOptions = {
				type: 'POST',
				data: that.serialize(),
				success: function(data) {
					if (data.content) {
						$(that).after(data.content);
						$(that).remove();
						$(that).find('button').removeAttr('disabled');
					}
				}
			};
			
			ajaxRequest(requestURL, requestOptions);
			
			//$(this).find('button').attr('disabled', 'disabled');
		}
		
		event.preventDefault();
	});
	$('.add_expertise_input').on('click', function(){
        $('.expertised').append('.add_expertise_field');
    });
    $('.add_interests_input').on('click', function(){
        alert("kjsdkljklj");
    });
});

