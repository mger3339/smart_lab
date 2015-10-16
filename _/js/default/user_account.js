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
        var ddd = $('.add_expertise_field:last').clone();
        ddd.find('input').each(function(i,v){
           $(v).attr('data-index', parseInt($(v).attr('data-index'))+1);
        });
        $('.add_expertise_field:last').after(ddd);
    });

    $('body').on('click','.add_interests_input', function(){
        var eee = $('.add_interests_field:last').clone();
        eee.find('input').each(function(i,v){
            $(v).attr('data-index', parseInt($(v).attr('data-index'))+1);
        });
        $('.add_interests_field:last').after(eee);
    });

    $('body').on('click','.close_expertise',function(e){
        e.stopPropagation();
        var requestURL = $(this).attr('data-delete-url');
        var expertise_id = $(this).attr('id');
        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {expertise_id:expertise_id},
                success: function (data) {
                    console.log(data);
                    $(e.target).parent().remove();
                }
            };
            ajaxRequest(requestURL, requestOptions);
        }
    });

    $('body').on('click','.close_interests',function(e){
        e.stopPropagation();
        var requestURL = $(this).attr('data-delete-url');
        var interests_id = $(this).attr('id');
        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {interests_id:interests_id},
                success: function (data) {
                    console.log(data);
                    $(e.target).parent().remove();
                }
            };
            ajaxRequest(requestURL, requestOptions);
        }
    });

    $('body').on('click','.add_expertise', function(e){
        var expertise = $(e.target).closest('.add_expertise_field').find('input').val();
        var requestURL = $(this).attr('data-url');
        then = $(this);

        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {expertise:expertise},
                success: function (data) {
                    alert($('.expertise_name').length);
                    if(data.status != 'error')
                    {
                        if($('.expertise_name').length > 0){
                            $('.expertise_name:last').after('<div class="expertise_name"><span class="expertise_span">'+data.expertise.expertise+'</span><span class="close_expertise">X</span></div>');
                            if($('.add_expertise_field').find('input').length == 1){
                                then.parent().find('input').val('');
                            }
                            else{
                                then.parent().remove();
                            }
                        }
                        else{
                            var div = '<div class="expertise_name"><span class="expertise_span">'+data.expertise.expertise+'</span><span class="close_expertise">X</span></div>';
                            $('.expertise').append(div);
                            $('.add_expertise_field>input').val('');
                        }
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
    });

    $('body').on('click','.add_interests', function(e){
        var interests = $(e.target).closest('.add_interests_field').find('input').val();
        var requestURL = $(this).attr('data-url');
        then = $(this);
        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {interests:interests},
                success: function (data) {
                    if(data.status != 'error'){
                        if($('.interests_name').length > 0){
                            $('.interests_name:last').after('<div class="interests_name"><span class="interests_span">'+data.interests.interests+'</span><span class="close_interests">X</span></div>');
                            if($('.add_interests_field').find('input').length == 1){
                                then.parent().find('input').val('');
                            }
                            else{
                                then.parent().remove();
                            }
                        }
                        else{
                            var div = '<div class="interests_name"><span class="interests_span">'+data.interests.interests+'</span><span class="close_interests">X</span></div>';
                            $('.interests').append(div);
                            $('.add_interests_field>input').val('');
                        }
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
    });

    $('body').on('keyup','.add_interests_field > input', function(e) {
        var x = $(e.target).offset().left - 74;
        var y = $(e.target).offset().top - 82;
        $('.interests_autocomplete').css('left', x).css('top', y).attr('data-ul-index',$(e.target).attr('data-index')).show();
        var text = $(this).val();
        var requestURL = $(this).attr('data-url');
        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {text:text},
                success: function (data) {
                    $(".interests_autocomplete ul").empty();
                    $.each(data.result, function(i,v){
                        $('.interests_autocomplete ul').append('<li class="autocomplete_li" id="' + v.id +'">'+v.interests+'</li>')
                    });
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
    });

    $('body').on('click', '.autocomplete_li', function(e){
        var text = $(e.target).text();
        var index = $(e.target).closest('.interests_autocomplete').attr('data-ul-index');
        $(e.target).closest('.interests').find('.add_interests_field:eq('+index+') > input').val(text);
        $('.interests_autocomplete').hide();
    });

    $('body').on('keyup','.add_expertise_field > input', function(e) {
        var x = $(e.target).offset().left - 74;
        var y = $(e.target).offset().top - 82;
        $('.expertise_autocomplete').css('left', x).css('top', y).attr('data-ul-index', $(e.target).attr('data-index')).show();
        var text = $(this).val();
        var requestURL = $(this).attr('data-url');
        if (requestURL) {
            var requestOptions = {
                type: 'GET',
                data: {text: text},
                success: function (data) {
                    $(".expertise_autocomplete ul").empty();
                    $.each(data.result, function (i, v) {
                        $('.expertise_autocomplete ul').append('<li class="autocomplete_li" id="' + v.id + '">' + v.expertise + '</li>')
                    });
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
    });

    $('body').on('click', '.autocomplete_li', function(e){
        var text = $(e.target).text();
        var index = $(e.target).closest('.expertise_autocomplete').attr('data-ul-index');
        $(e.target).closest('.expertise').find('.add_expertise_field:eq('+index+') > input').val(text);
        $('.expertise_autocomplete').hide();
    });

    $('body').on('click', function(e){
        //e.stopPropogation();
        var expertise_length = $(e.target).closest('.expertise_autocomplete').length;
        var interest_length = $(e.target).closest('.interests_autocomplete').length;
       if(expertise_length != 1){
           $('.expertise_autocomplete').hide();
       }
        if(interest_length != 1){
            $('.interests_autocomplete').hide();
        }
    });
});

