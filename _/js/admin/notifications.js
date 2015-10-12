$(document).ready(function()
{
	var foundItems = [];
	var ajaxRequestOngoing = false;

	// Resolve API URL
	var resolveURL = function(type)
	{
		var URL = false;

		switch (type)
		{
			case 'user':
				URL = 'admin/applications/searchUsers';
				break;

			case 'group':
				URL = 'admin/applications/searchGroups';
				break;
		}

		return URL;
	};

	// Build HTML item
	var buildItem = function(type, data)
	{
		var item = '<p data-type="' + type + '" data-id="' + data['id'] + '">';;
		switch (type)
		{
			case 'user':
        		item += data['firstname'] + ' ' + data['lastname'];
				break;

			case 'group':
    			item += data['name'];
    			break;
		}

		return item + '</p>';
	}

	// Filter added items by item type
	var filterAddedItems = function(selectedItemsContainerSelector, type)
	{
		return $(selectedItemsContainerSelector).find('p').filter(function()
		{ 
			return $(this).data('type') == type
		});
	}

	// Do AJAX API call
	var searchItems = function(options)
	{
		var addedItems = filterAddedItems(options.selectedItemsContainerSelector, options.type);

    	var ids = [];
	    $.each(addedItems, function( index, value )
	    {
	    	ids.push($(value).data('id'));
	    });

        var requestOptions = {
            type: "GET",
            data:{
                keyword: options.keyword,
                ids: ids
            },
            success: function(data)
            {
            	// Should we clear all data?
            	if (options.clearResults == true)
            	{
            		foundItems = [];
			    	$(options.resultsContainer).empty();
            	}

			    if(data.length)
			    {
			    	// Append found results & show them
			        $(options.resultsContainer).fadeIn();

			        $.each(data, function(index, value)
			        {
			        	item = buildItem(options.type, value);			        	
			            $(options.resultsContainer).append(item);

			            foundItems.push(item);
			        });
			    }
			    else if (options.lastRequest == true && foundItems.length == 0)
			    {
			    	// If no results and last request, hide results container
			        $(options.resultsContainer).fadeOut();
			    }

			    // If last request, unlock AJAX calls
			    if (options.lastRequest == true)
			    {
			    	ajaxRequestOngoing = false;
			    }
            }
        };

        return ajaxRequest(resolveURL(options.type), requestOptions);
	}

	// Define keyup action for users & groups search
    $(document).on('keyup', '#recipients_search', function(event)
    {
    	if (ajaxRequestOngoing == false)
    	{
    		ajaxRequestOngoing = true;

	    	var options = {
	    		keyword : $(this).val(),
	    		type : 'user',
	    		selectedItemsContainerSelector : '.recipients_tags_div',
	    		resultsContainer : '.searched_recipients',
	    		clearResults : true,
	    		lastRequest : false
	    	};

	    	// First search users
	    	searchItems(options).always(function()
	    	{
	    		// Then search groups
	    		options.type = 'group';
	    		options.clearResults = false;
	    		options.lastRequest = true;

	    		searchItems(options);
	    	});

	    	event.preventDefault();    		
    	}
    });

    // Define item click action for users & groups search
    $(document).on('click', '.searched_recipients p', function(event)
    {
        var selectedItem = $(this);
        var addedItems = filterAddedItems('.recipients_tags_div', selectedItem.data('type'));

        var itemAlreadyAdded = false;
        $.each(addedItems, function(index, addedItem)
        {
        	if (selectedItem.data('id') == $(addedItem).data('id'))
        	{
				itemAlreadyAdded = true;
        	}
        });

        if( ! itemAlreadyAdded)
        {
	        var newItem = $(this).clone();
	        newItem.append('<span class="remove_item"> x</span>');

            $('.recipients_tags_div').prepend(newItem);
            $(".searched_recipients").fadeOut();
            $('#recipients_search').val('');

            updateSelectedValues(selectedItem.data('type'), '.recipients_tags_div', '#' + selectedItem.data('type') + '_ids');
        }
    });

    // Remove item action
    $(document).on('click', '.remove_item', function(event)
    {
    	var type = $(this).parent().data('type');
    	$(this).parent().remove();
    	updateSelectedValues(type, '.recipients_tags_div', '#' + type + '_ids');
    });

    // Update selected values for requested input
    function updateSelectedValues(type, selectedItemsContainerSelector, inputSelector)
    {
		var addedItems = filterAddedItems(selectedItemsContainerSelector, type);

        var inputValue = '';
        $.each(addedItems, function(index, addedItem)
        {
        	inputValue += $(addedItem).data('id') + ',';
        });
        inputValue = inputValue.substring(0, inputValue.length - 1);

        $(inputSelector).val(inputValue);
    }

});