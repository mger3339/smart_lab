// user account functionality
$(document).ready(function() {
	
	$('.user-account-content').on('submit', '.user-account-form', function(event) {
		var that = $(this);
		var requestURL = $(this).attr('action');
		
		if (requestURL) {
			
			var requestOptions = {
				type: 'POST',
				data: $(that).serialize(),
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

    $('body').on('click','.add_expertise_input', function(e){
        var ddd = $('.add_expertise_field').clone().first();
        $('.add_expertise_field:last').after(ddd);
    });

    $('body').on('click','.add_interests_input', function(){
        var eee = $('.add_interests_field').clone().first();
        $('.add_interests_field:last').after(eee);
    });

    $('.close_expertise').on('click',function(e){
        var requestURL = $(this).attr('data-delete-url');
        var expertise_id = $(this).attr('id');
        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {expertise_id:expertise_id},
                success: function (data) {
                    console.log(data);
                    $(e.target).closest('.expertise_name').remove();
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
    });

    $('.close_interests').on('click',function(e){
        $(e.target).closest('.interests_name').remove();
    });

    $('body').on('click','.add_expertise', function(e){
        var expertise = $(e.target).closest('.add_expertise_field').find('input').val();
        var requestURL = $(this).attr('data-url');

        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {expertise:expertise},
                success: function (data) {
                    console.log(data);
                    if($('.expertise_name').length > 0){
                        $('.expertise_name:last').after('<div class="expertise_name"><span class="expertise_span">'+data.expertise.expertise+'</span><span class="close_expertise">X</span></div>');
                    }
                    else{
                        var div = '<div class="expertise_name"><span class="expertise_span">'+data.expertise.expertise+'</span><span class="close_expertise">X</span></div>';
                        $('.expertise').append(div);
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
    });
});

