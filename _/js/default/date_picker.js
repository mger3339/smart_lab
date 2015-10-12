// date picker

var initDatePickers = function() {
	
	$('.date-picker').focus(function() {
		$(this).parent().addClass('date-picker-wrap-active');
	});
	
	$('.date-picker').blur(function() {
		$(this).parent().removeClass('date-picker-wrap-active');
	});
	
	$('.date-picker').each(function() {
		
		var datePickerOptions = {
			cssName: 'smartlab',
			borderSize: 0,
			calendarOffset: { x: -2, y: 0 },
			hideOnClick: false
		};
		
		var selectedDate = $(this).attr('data-date');
		
		if (selectedDate) {
			datePickerOptions.selectedDate = new Date(selectedDate * 1000);
		}
		
		$(this).glDatePicker(datePickerOptions);
	});
}

$(document).ready(function() {
	
	initDatePickers();
});

