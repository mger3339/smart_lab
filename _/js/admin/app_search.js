$(document).ready(function(){

    $(document).on('keyup', '#users_search', function(event) {

        var requestURL = "admin/applications/searchUsers";
        var added_users = $('.users_tags_div').find('p');
        var ids = [];
        $.each(added_users, function( index, value ) {
            ids.push($(value).attr('id'));
        });
        if (requestURL) {
            var requestOptions = {
                type: "GET",
                data:{
                    keyword: $(this).val(),
                    ids: ids
                },
                success: function(data) {
                    $('.searched_users').empty();

                    if(data.length)
                    {
                        $(".searched_users").fadeIn();
                        $.each(data, function( index, value ) {
                            var user = "<p id='"+value['id']+"' >"+value['firstname']+' '+value['lastname']+"</p>";
                            $('.searched_users').append(user);
                        });
                    }else{
                        $(".searched_users").fadeOut();
                    }
                }

            };

            ajaxRequest(requestURL, requestOptions);
        }

        event.preventDefault();
    });

    $(document).on('click', '.searched_users p', function(event) {
        var added_users = $('.users_tags_div').find('p');
        var is_exist = false;
        var this_user = $(this);

        $.each(added_users, function( index, value ) {
            if($(this_user).attr('id') == $(value).attr('id'))
               is_exist = true;
        });
        if(!is_exist){
            var user =  "<p id='"+$(this).attr('id')+"' >"+$(this).html()+"<span id='"+$(this).attr('id')+"' class='remove_user'>"+'x'+"</span>"+"</p>";
            var span = "<span>"+user+"</span>"
            $(span).insertBefore( "#users_search" );
            $(".searched_users").fadeOut();
            $('#users_search').val('');
            is_exist = false;
            changeValue('.users_tags_div','#user_id');
        }

    });

    $(document).on('click', '.remove_user', function(event) {
        var added_users = $('.users_tags_div').find('p');
        var this_user = $(this);
        $.each(added_users, function( index, value ) {
            if($(this_user).attr('id') == $(value).attr('id'))
                $(value).parent().remove();
                changeValue('.users_tags_div','#user_id');
        });
    });

    $(document).on('keyup', '#groups_search', function(event) {
        var added_groups = $('.groups_tags_div').find('p');
        var ids = [];
        var requestURL = "admin/applications/searchGroups";
        $.each(added_groups, function( index, value ) {
            ids.push($(value).attr('id'));
        });

        if (requestURL) {
            var requestOptions = {
                type: "GET",
                data:{
                    keyword: $(this).val(),
                    ids: ids
                },
                success: function(data) {
                    $('.searched_groups').empty();
                    if(data.length)
                    {
                        $(".searched_groups").fadeIn();
                        $.each(data, function( index, value ) {
                            var group = "<p id='"+value['id']+"' >"+value['name']+"</p>";
                            $('.searched_groups').append(group);
                        });
                    }else{
                        $(".searched_groups").fadeOut();
                    }
                }

            };

            ajaxRequest(requestURL, requestOptions);
        }

        event.preventDefault();
    });

    $(document).on('click', '.searched_groups p', function(event) {
        var added_groups = $('.groups_tags_div').find('p');
        var is_exist = false;
        var this_group = $(this);

        $.each(added_groups, function( index, value ) {
            if($(this_group).attr('id') == $(value).attr('id'))
                is_exist = true;
        });
        if(!is_exist){
            var group =  "<p id='"+$(this).attr('id')+"' >"+$(this).html()+"<span id='"+$(this).attr('id')+"' class='remove_group'>"+'x'+"</span>"+"</p>";
            var span = "<span>"+group+"</span>"
            $(span).insertBefore( "#groups_search" );
            $(".searched_groups").fadeOut();
            $('#groups_search').val('');
            is_exist = false;
            changeValue('.groups_tags_div','#group_id');
        }

    });

    $(document).on('click', '.remove_group', function(event) {
        var added_groups = $('.groups_tags_div').find('p');
        var this_group = $(this);
        $.each(added_groups, function( index, value ) {
            if($(this_group).attr('id') == $(value).attr('id'))
                $(value).parent().remove();
            changeValue('.groups_tags_div','#group_id');
        });
    });

    $(document).on('keyup', '#facilitators_search', function(event) {
        var added_acilitators = $('.facilitators_tags_div').find('p');
        var ids = [];
        var requestURL = "admin/applications/searchFacilitators";
        $.each(added_acilitators, function( index, value ) {
            ids.push($(value).attr('id'));
        });

        if (requestURL) {
            var requestOptions = {
                type: "GET",
                data:{
                    keyword: $(this).val(),
                    ids: ids
                },
                success: function(data) {
                    $('.searched_facilitators').empty();
                    if(data.length)
                    {
                        $(".searched_facilitators").fadeIn();
                        $.each(data, function( index, value ) {
                            var facilitator = "<p id='"+value['id']+"' >"+value['firstname']+' '+value['lastname']+"</p>";
                            $('.searched_facilitators').append(facilitator);
                        });
                    }else{
                        $(".searched_facilitators").fadeOut();
                    }
                }

            };

            ajaxRequest(requestURL, requestOptions);
        }

        event.preventDefault();
    });

    $(document).on('click', '.searched_facilitators p', function(event) {

        var added_facilitators = $('.facilitators_tags_div').find('p');
        var is_exist = false;
        var this_facilitator = $(this);

        $.each(added_facilitators, function( index, value ) {
            if($(this_facilitator).attr('id') == $(value).attr('id'))
                is_exist = true;
        });
        if(!is_exist){
            var facilitator =  "<p id='"+$(this).attr('id')+"' >"+$(this).html()+"<span id='"+$(this).attr('id')+"' class='remove_facilitator'>"+'x'+"</span>"+"</p>";
            var span = "<span>"+facilitator+"</span>"
            $(span).insertBefore("#facilitators_search");
            is_exist = false;
            $(".searched_facilitators").fadeOut();
            $('#facilitators_search').val('');
            changeValue('.facilitators_tags_div','#facilitator_id');
        }

    });

    $(document).on('click', '.remove_facilitator', function(event) {
        var added_facilitators = $('.facilitators_tags_div').find('p');
        var this_facilitator = $(this);
        $.each(added_facilitators, function( index, value ) {
            if($(this_facilitator).attr('id') == $(value).attr('id'))
                $(value).parent().remove();
            changeValue('.facilitators_tags_div','#facilitator_id');
        });
    });



    function changeValue(parent,input){
        var added_users = $(parent).find('p');
        var input_val = '';
        $.each(added_users, function( index, value ) {
            input_val += value['id']+',';
        });
        input_val = input_val.substring(0, input_val.length - 1);
        $(input).val(input_val);
    }

});