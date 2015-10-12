// app navigation toggle

$(document).ready(function() {
	
	var appNavMode = $('#app-navigation-toggle').attr('data-nav-mode');
	
	if (!appNavMode) {
		
		appNavMode = 'main';
		
		$('#app-navigation-toggle').attr('data-nav-mode', appNavMode);
		$('#main-navigation').css('left', 0);
	}
	
	if (appNavMode == 'admin') {
		
		$('#main-navigation').css({left: "-100%"});
	}
	
	$('#app-navigation-toggle').on('click', function(event) {
		
		appNavMode = $(this).attr('data-nav-mode');
		
		if (appNavMode == 'main') {
			
			$(this).addClass('admin');
			
			appNavMode = 'admin';
			
			$('#main-navigation').animate({left: "-100%"}, 600, function() {});
			
		} else {
			
			$(this).removeClass('admin');
			
			appNavMode = 'main';
			
			$('#main-navigation').animate({left: 0}, 600, function() {});
		}
		
		$(this).attr('data-nav-mode', appNavMode);
		
		event.preventDefault();
	});
	
});