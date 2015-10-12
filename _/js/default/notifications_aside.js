$(document).ready(function()
{
	// Aside animation
	var animateAside = function(marginRightValue)
	{
		$('#notifications-aside').stop();

		$('#notifications-aside').animate({
			marginRight: marginRightValue
		}, 400);		
	}
	
	// Show/hide click event
	$(document).on('click', '#user-notifications-nav-btn', function(event)
	{
		animateAside($('#notifications-aside').hasClass('visible') ? -($('#notifications-aside').width() + 10) : 0);
		$('#notifications-aside').toggleClass('visible');
	});

	// Position, prepare tabas and show aside
	$('#notifications-aside')
		.insertAfter('#header')
		.tabs()
		.show();

	// Convert timestamps to timeago format
	$('abbr.timeago').timeago();
});

