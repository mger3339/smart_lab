// flash message
$(document).ready(function() {
	
	var displayFlashMessage = function() {
		$('#flash-message').css('top', $(document).scrollTop());
		$('#flash-message').show();
		if (!flashMessageVisible) {
			$('#flash-message-content').animate({top: 0}, 300);
			flashMessageVisible = true;
		}
	}
	
	var hideFlashMessage = function() {
		if (flashMessageVisible) {
			$('#flash-message-content').animate({top: "-100px"}, 300, function() {
				$('#flash-message').hide();
			});
			flashMessageVisible = false;
		}
	}
	
	$('#flash-message').on('click', function() {
		hideFlashMessage();
	});
	
	$('#flash-message').on('clickoutside', function() {
		hideFlashMessage();
	});
	
	$(document).ajaxSuccess(function(e, xhr, settings) {
		if (xhr.responseText) {
			var obj = $.parseJSON(xhr.responseText);
			if (obj.status && obj.message) {
				$('#flash-message-content').removeClass();
				$('#flash-message-content').addClass(obj.status);
				$('#flash-message').find('.message').text(obj.message);
				displayFlashMessage();
			}
		}
	});
	
	$(document).scroll(function() {
		if (flashMessageVisible) {
			$('#flash-message').css('top', $(document).scrollTop());
		}
	});
	
	var flashMessage = $('#flash-message').find('.message').text();
	var flashMessageVisible = false;
	
	if (flashMessage) {
		displayFlashMessage();
	}
	
});
