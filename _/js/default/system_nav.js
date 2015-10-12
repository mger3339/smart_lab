// system nav UI

var systemNavOpen = false;

var openSystemNav = function(systemNavItemID) {
	
	$('.system-nav-item').hide();
	$(systemNavItemID).show();
	
	var systemNavHeight = $(systemNavItemID).prop('scrollHeight');
	
	$('.system-nav-items').animate({height: systemNavHeight + "px"}, 300, function() {
		
	});
	
	systemNavOpen = true;
}

var closeSystemNav = function(linkURL) {
	
	$('.system-nav-items').animate({height: "0px"}, 300, function() {
		
		$('.system-nav-item').hide();
		
		if (linkURL) {
			location.replace(linkURL);
		}
	});
	
	systemNavOpen = false;
}

$(document).ready(function() {
	
	$('.system-nav-btn').on('click', function() {
		
		if ($(document).scrollTop() > 0) {
			$('html, body').animate({
				scrollTop: 0
			}, 300);
		}
		
		var systemNavItemID = $(this).attr('data-item-id');
		
		openSystemNav(systemNavItemID);
	});
	
	$('#system-nav').on('clickoutside', function() {
		if (systemNavOpen) {
			closeSystemNav(false);
		}
	});
	
	$('.system-nav-item').find('a').on('click', function(event) {
		
		event.preventDefault();
		
		var linkURL = $(this).attr('href');
		
		if (linkURL) {
			closeSystemNav(linkURL);
		}
	});
	
});
