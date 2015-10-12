// T Map App layout UI functionality

// vars & functions for setting layout dimensions
var headerH = 0;
var footerH = 0;
var listHeadH = 0;

var updateLayout = function() {
	
	var windowH = $(window).height();
	var contentH = windowH - headerH - footerH;
	
	$('#index').height(contentH);
	$('#calendar').height(contentH);
	$('#list-column').height(contentH);
		
	$('#list-column-body-wrap').height(contentH - listHeadH);
	
	$('#calendar-back-wrap').height(contentH);
	$('#calendar-back').height(contentH);
	
	$('#calendar-body-wrap').height(contentH - listHeadH);
	$('.year').height(contentH);
	$('.cal-unit').height(contentH);
	
	$('.list-column-body').css('min-height', (contentH - listHeadH) + 'px');
	$('.calendar-body').css('min-height', (contentH - listHeadH) + 'px');
	
	$('#workstream-wrap').css('min-height', (windowH - footerH) + 'px');
	$('#milestone-wrap').css('min-height', (windowH - footerH) + 'px');
};


// vars & functions for calendar scolling functionality
var initScrollUI = function() {
	
	$('#calendar-body-wrap').scroll(function() {
		$('#list-column-body-wrap').scrollTop($(this).scrollTop());
		$('#calendar-back-wrap').scrollLeft($(this).scrollLeft());
	});
	
	$('#list-column-body-wrap').scroll(function() {
		$('#calendar-body-wrap').scrollTop($(this).scrollTop());
	});
	
	$('#calendar-back-wrap').scroll(function() {
		$('#calendar-body-wrap').scrollLeft($(this).scrollLeft());
	});
};


$(document).ready(function() {
	
	// set & prep layout element heights for dynamic window resizing
	headerH = $('#header').outerHeight();
	footerH = $('#footer').outerHeight();
	listHeadH = $('#list-column-head').outerHeight();
	
	$(window).resize(function() {
		updateLayout();
	});
	
	updateLayout();
	
	
	// display the workstreams column & calendar
	$('#list-column-workstreams').fadeIn(600);
	$('#calendar-workstreams').fadeIn(600);
	
	
	// set up calendar scolling functionality
	setTimeout(initScrollUI, 300);
});
