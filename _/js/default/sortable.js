// sortable

var initSortable = function() {
	
	$('.sortable-list').sortable({
		handle: '.handle',
		update: function(event, ui) {
			
			var item = ui.item;
			var parent = $(item).parent();
			var updateURL = parent.attr('data-sort-update');
			
			if (updateURL) {
				var itemIDs = [];
				$(parent).children().each(function(i, el) {
					itemID = $(el).attr('data-id');
					if (itemID) itemIDs.push(itemID);
				});
				var serialisedIDs = itemIDs.join('-');
				
				updateURL = updateURL + '/' + serialisedIDs;
				
				ajaxRequest(updateURL, {});
			}
		}
	});
}

$(document).ready(function() {
	
	initSortable();
});

