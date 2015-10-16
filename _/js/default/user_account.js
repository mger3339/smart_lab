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
        var requestURL = $(this).attr('data-delete-url');
        var interests_id = $(this).attr('id');
        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {interests_id:interests_id},
                success: function (data) {
                    console.log(data);
                    $(e.target).closest('.interests_name').remove();
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
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

    $('body').on('click','.add_interests', function(e){
        var interests = $(e.target).closest('.add_interests_field').find('input').val();
        var requestURL = $(this).attr('data-url');

        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {interests:interests},
                success: function (data) {
                    console.log(data.interests.interests);
                    if($('.interests_name').length > 0){
                        $('.interests_name:last').after('<div class="interests_name"><span class="interests_span">'+data.interests.interests+'</span><span class="close_interests">X</span></div>');
                    }
                    else{
                        var div = '<div class="interests_name"><span class="interests_span">'+data.interests.interests+'</span><span class="close_interests">X</span></div>';
                        $('.interests').append(div);
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
    });
});

