// data rows functionality
$(document).ready(function() {

	var formActive = false;

	var hideRowButtons = function() {
		$('.add-row-btn').hide();
		$('.edit-row-btn').hide();
		$('.delete-row-btn').hide();
		$('.sort-row-btn').hide();
		$('.import-row-btn').hide();
        //$('.set-reset-row-btn').hide();
        $('.edit_user_role').hide();
        $('.edit_user_group').hide();
        $('.select_all_users').hide();
        $('.deselect_all_users').hide();
	}

	var showRowButtons = function() {
		$('.add-row-btn').show();
		$('.edit-row-btn').show();
		$('.delete-row-btn').show();
		$('.sort-row-btn').show();
		$('.import-row-btn').show();
		//$('.set-reset-row-btn').show();
		$('.edit_user_role').show();
		$('.edit_user_group').show();
		$('.select_all_users').show();
        $('.deselect_all_users').show();
	}

	// add - put row
	$('.add-row').on('click', '.add-row-btn, .import-row-btn', function(event) {
		var dataRow = $(this).siblings('.data-rows-list').find('.data-row');
		var requestURL = $(this).attr('data-url');
        console.log(dataRow);
		if (requestURL) {

			var requestOptions = {
				success: function(data) {
					if (data.status == 'success') {
						$(dataRow).append(data.content);
						hideRowButtons();
						initFormUIElements();
					}
				}
			};

			ajaxRequest(requestURL, requestOptions);
		}

		event.preventDefault();
	});

	$('.add-row').on('submit', '.add-row-form', function(event) {

		var dataRow = $(this).parents('.data-row');
		var dataRowForm = $(this);
		var requestURL = $(this).attr('action');

		if (requestURL) {

			var requestOptions = {
				type: 'POST',
				data: $(dataRowForm).serialize(),
				success: function(data) {
                    console.log(data);
					if (data.status == 'error' && data.content) {
						$(dataRow).html(data.content);
						initFormUIElements();
					}
				}
			};

			ajaxRequest(requestURL, requestOptions);
		}

		event.preventDefault();
	});

    $('.data-role-row').on('submit', '.user-role-row-form', function(event) {
        var dataRow = $(this).parents('.data-row');
        var dataRowForm = $(this);
        var requestURL = $(this).attr('action');
        var val = $('button[type=submit][clicked=true]').attr('name');
        if (requestURL) {

            var requestOptions = {
                type: 'POST',
                data: $(dataRowForm).serialize(),
                success: function(data) {
                    if (data.status == 'error' && data.content) {
                        $(dataRow).html(data.content);
                        initFormUIElemserializeents();
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }

        event.preventDefault();
    });

    $('.data-role-row').on('submit', '.user-group-row-form', function(event) {
        var dataRow = $(this).parents('.data-role-row');
        var dataRowForm = $(this);
        var requestURL = $(this).attr('action');
        var action = $('button[type=submit]:focus').attr('name');

        if (requestURL) {

            var requestOptions = {
                type: 'POST',
                data: $(dataRowForm).serialize()+'&action='+action,
                success: function(data) {
                    if (data.status == 'error' && data.content) {
                        console.log(dataRow);
                        $(dataRow).html(data.content);
                        initFormUIElements();
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }
        event.preventDefault();
        return false;
    });

    $('.add-row').on('submit', '.add_new_groups', function(event) {

        var dataRow = $(this).parents('.new_groups_div');
        var dataRowForm = $(this);
        var requestURL = $(this).attr('action') + "/";

        if (requestURL) {
            var requestOptions = {
                type: 'POST',
                data: $(dataRowForm).serialize(),
                success: function(data) {
                    if(data){
                        if(data.insert){
                            var option = "<option value='"+data.insert+"'>"+$('#new_groups').val()+"</option>";
                            $("#multi_select").prepend(option);
                        }else{
                            alert('This group is already exists');
                        }
                        var form_token = $('.import-row-form input[name="'+data.token.name+'"]');
                        $(form_token).val(data.token.value);
                        dataRow.empty();
                        $(dataRow).html(data.content);
                        initFormUIElements();
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }

        event.preventDefault();
    });


    $('.add-row').on('submit', '.import-row-form', function(event) {

        var dataRow = $(this).parents('.data-row');
        var dataRowForm = $(this);
        var requestURL = $(this).attr('action');
        var formData = new FormData($('.import-row-form')[0]);

        if (requestURL) {

            var requestOptions = {
                type: 'POST',
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.content) {
                        $(dataRow).html(data.content);
                        initFormUIElements();
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }

        event.preventDefault();
    });

	// cancel add row operation
	$('.add-row').on('click', '.cancel-add-btn', function(event) {

		var dataRow = $(this).parents('.data-row');

		$(dataRow).find('.add-row-form').remove();

		showRowButtons();

		event.preventDefault();
	});

    // cancel import row operation
    $('.add-row').on('click', '.cancel-import-btn', function(event) {
        var dataRow = $(this).parents('.data-row');

        $(dataRow).find('.import-row-form').remove();
        $('.add-row').find('.new_groups_div').remove();

        showRowButtons();

        event.preventDefault();
    });

    // cancel add row operation
    $('.users').on('click', '.cancel-user-role-btn', function(event) {

        var dataRow = $(this).parents('.data-role-row');

        $(dataRow).find('.user-role-row-form').remove();

        showRowButtons();

        event.preventDefault();
    });

    // cancel add row operation
    $('.users').on('click', '.cancel-user-group-btn', function(event) {

        var dataRow = $(this).parents('.data-role-row');

        $(dataRow).find('.user-group-row-form').remove();

        showRowButtons();
        $('#users_list').show();
        event.preventDefault();
    });

	// delete row
	$('.data-rows-list').on('submit', '.delete-row-form', function(event) {

		if ($(this).hasClass('confirmed')) {
			return;
		}

		var confirmDeleteMsg = $(this).attr('data-confirm');
		var confirmDelete = (confirmDeleteMsg) ? confirm(confirmDeleteMsg) : true;

		if (confirmDelete) {

			var reConfirmDeleteMsg = $(this).attr('data-re-confirm');
			var reConfirmDelete = (reConfirmDeleteMsg) ? confirm(reConfirmDeleteMsg) : true;

			if (reConfirmDelete) {

				$(this).addClass('confirmed');
				$(this).submit();
			}
		}

		event.preventDefault();
	});

	// edit - update row
	$('.data-rows-list').on('click', '.edit-row-btn', function(event) {
		var dataRow = $(this).parents('.data-row');
		var dataRowItem = $(this).parents('.row');
		var requestURL = $(this).attr('data-url');
		if(!dataRow.length){
            dataRow = $('.data-row');
        }
		if (requestURL) {

			var requestOptions = {
				success: function(data) {
					if (data.status == 'success') {
						$(dataRow).append(data.content);
						$(dataRowItem).hide();
						hideRowButtons();
						initFormUIElements();
					}
				}
			};

			ajaxRequest(requestURL, requestOptions);
		}

		event.preventDefault();
	});

    // edit role - update row
    $('.users_options').on('click', '.edit_user_role', function(event) {
        var dataRow = $(this).parents('.data-role-row');
        var dataRowItem = $(this).parents('.row');
        var requestURL = $(this).attr('data-url');

        if(!dataRow.length){
            dataRow = $('.data-role-row');
        }
        var ids = [];
        $("input.check_users:checkbox:checked").each(function() {
            ids.push($(this).attr('id'));
        });

        if (requestURL && ids.length) {

            var requestOptions = {
                data:{user_ids: ids},
                success: function(data) {
                    if (data.status == 'success') {
                        $(dataRow).append(data.content);
                        $(dataRowItem).hide();
                        hideRowButtons();
                        initFormUIElements();
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }else{
            alert('Please select users.')
        }

        event.preventDefault();
    });

	$('.data-rows-list').on('submit', '.edit-row-form', function(event) {

		var dataRow = $(this).parents('.data-row');
		var dataRowForm = $(this);
		var requestURL = $(this).attr('action');
        var password = $(this).find("input[name='password']").val();
		if (requestURL) {

			var requestOptions = {
				type: 'POST',
				data: $(dataRowForm).serialize(),
				success: function(data) {
					if (data.status == 'success') {
						$(dataRow).html(data.content);
						showRowButtons();
					} else {
						$(dataRowForm).remove();
						$(dataRow).append(data.content);
						initFormUIElements();
					}
				}
			};

			ajaxRequest(requestURL, requestOptions);
		}

		event.preventDefault();
	});

    //$('.data-rows-list').on('submit', '.set-reset-row-form', function(event) {
    //
    //    var dataRow = $(this).parents('.data-row');
    //    var dataRowForm = $(this);
    //    var requestURL = $(this).attr('action');
    //
    //    if (requestURL) {
    //
    //        var requestOptions = {
    //            type: 'POST',
    //            data: $(dataRowForm).serialize(),
    //            success: function(data) {
    //                if (data.status == 'success') {
    //                    $(dataRow).html(data.content);
    //                    showRowButtons();
    //                } else {
    //                    $(dataRowForm).remove();
    //                    $(dataRow).append(data.content);
    //                    initFormUIElements();
    //                }
    //            }
    //        };
    //
    //        ajaxRequest(requestURL, requestOptions);
    //    }
    //
    //    event.preventDefault();
    //});

    // edit - update row
    //$('.data-rows-list').on('click', '.set-reset-row-btn', function(event) {
    //    var dataRow = $(this).parents('.data-row');
    //    var dataRowItem = $(this).parents('.row');
    //    var requestURL = $(this).attr('data-url');
    //    if(!dataRow.length){
    //        dataRow = $('.data-row');
    //    }
    //    if (requestURL) {
    //
    //        var requestOptions = {
    //            success: function(data) {
    //                if (data.status == 'success') {
    //                    $(dataRow).append(data.content);
    //                    $(dataRowItem).hide();
    //                    hideRowButtons();
    //                    initFormUIElements();
    //                }
    //            }
    //        };
    //
    //        ajaxRequest(requestURL, requestOptions);
    //    }
    //
    //    event.preventDefault();
    //});

	// cancel edit row operation
	$('.data-rows-list').on('click', '.cancel-edit-btn', function(event) {

		var dataRow = $(this).parents('.data-row');

		$(dataRow).find('.edit-row-form').remove();
		$(dataRow).find('.row').show();

		showRowButtons();

		event.preventDefault();
	});

    // cancel set/reset row operation
    //$('.data-rows-list').on('click', '.cancel-set-reset-btn', function(event) {
    //
    //    var dataRow = $(this).parents('.data-row');
    //
    //    $(dataRow).find('.set-reset-row-form').remove();
    //    $(dataRow).find('.row').show();
    //
    //    showRowButtons();
    //
    //    event.preventDefault();
    //});

	// form section toggling
	var triggerFormSectionToggle = function() {
		$('.form-toggle-select').trigger('change');
	}

	$('.data-rows-list').on('change', '.form-toggle-select', function(event) {

		var sectionsToHide = '.' + $(this).attr('id');

		$(sectionsToHide).hide();

		var sectionToDisplay = $(this).find(":selected").attr('data-section-id');

		$(sectionToDisplay).show();
	});

	// row section toggling
	$('.data-rows-list').on('click', '.toggle-btn', function(event) {

		$(this).toggleClass('toggle-btn-active');

		$(this).parents('.toggle-section').find('.toggle-content').slideToggle();

		event.preventDefault();
	});

	var initFormUIElements = function() {

		initDatePickers();
		initTouchDatePickers();
		triggerFormSectionToggle();
	}

    //reset search result
    $('.reset_search').on('click', function() {
        $('.search_users_form select').val("0");
        $('.search_users_form input[name="free_text"]').val("");
        window.location='/admin/users/';
        return false;
    })

    // edit groups - update row
    $('.users_options').on('click', '.edit_user_group', function(event) {
        var dataRow = $(this).parents('.data-role-row');
        var dataRowItem = $(this).parents('.row');
        var requestURL = $(this).attr('data-url');



        if(!dataRow.length){
            dataRow = $('.data-role-row');
        }
        var ids = [];
        $("input.check_users:checkbox:checked").each(function() {
            ids.push($(this).attr('id'));
        });

        if (requestURL && ids.length) {
            $('#users_list').hide();
            var requestOptions = {
                data:{user_ids: ids},
                success: function(data) {
                    if (data.status == 'success') {
                        console.log(dataRow);

                        $(dataRow).append(data.content);
                        $(dataRowItem).hide();
                        hideRowButtons();
                        initFormUIElements();
                    }
                }
            };

            ajaxRequest(requestURL, requestOptions);
        }else{
            alert('Please select users.')
        }

        event.preventDefault();
    });


    // update per page
    $('#wrap').on('change', '.per_page', function(event) {
        var perPage = $(this).val();
        var request_uri = window.location.search;
        var page_check = request_uri.indexOf("&page");
        var per_page_check = request_uri.indexOf("&per_page");
        var url = request_uri.split("&");
        var search_check = window.location.href.indexOf("/search?");
        if(search_check == -1){
            if(url[0] == "") {
                url[0] = "?";
                url[1] = "per_page="+perPage;
                url[2] = "page=1";
            }
            if(per_page_check !== -1) {
                url[1] = "per_page="+perPage;
            }else if(page_check !== -1) {
                url[0] = "?";
                url[2] = url[1];
                url[1] = "per_page="+perPage;
            }
            var full_url = url.join("&");
            var change_url = "/admin/users" + full_url;
        }else{
            var penultimate = url.length - 1;
            if(page_check !== -1){
                url[penultimate + 1] = url[penultimate];
                url[penultimate] = "per_page="+perPage;
            }else{
                url[penultimate + 2] = "page=1";
                url[penultimate + 1] = "per_page="+perPage;
            }
            var full_url = url.join("&");
            var change_url = "/admin/users/search" + full_url;
        }

        window.history.pushState("", "",change_url);
        window.location.reload();

        event.preventDefault();
    });

    $(".select_all_users").on('click', function(){
            $(".check_users").each(function(){
                this.checked = true;
            });
    });
    $(".deselect_all_users").on('click', function(){
        $(".check_users").each(function(){
            this.checked = false;
        });
    });

});

