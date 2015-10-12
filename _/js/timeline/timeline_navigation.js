// T Map App navigation UI functionality

// vars & functions for navigation between workstreams
// overview & milestones elements

var overview = true;

var initWorkstreamsOverview = function() {
	
	$('.workstream-head').fadeOut(600);
	
	$('#list-column-milestones').fadeOut(600);
	$('#calendar-milestones').fadeOut(600);
	
	$('.workstreams-overview-btn').fadeOut(600);
	
	setTimeout(function() {
		
		$('#list-column-workstreams').parent().prepend($('#list-column-workstreams'));
		$('#calendar-workstreams').parent().prepend($('#calendar-workstreams'));
		
		$('#list-column-workstreams').fadeIn(600);
		$('#calendar-workstreams').fadeIn(600);
		
		$('#list-column-milestones').find('.milestone-list').empty();
		$('#calendar-milestones').find('.milestone-list').empty();
		
		overview = true;
		
		initOverviewUpdateCycle();
		
	}, 600);
};

var initWorkstreamMilestones = function(workstreamID) {
	
	$('#list-column-workstreams').fadeOut(600);
	$('#calendar-workstreams').fadeOut(600);
	
	if (workstreamID != currentWorkstreamID) {
		$('#list-column-milestones > .milestone-list').empty();
		$('#calendar-milestones > .milestone-list').empty();
	}
	
	setTimeout(function() {
		
		$('#list-column-milestones').parent().prepend($('#list-column-milestones'));
		$('#calendar-milestones').parent().prepend($('#calendar-milestones'));
		
		$('.workstreams-overview-btn').fadeIn(600);
		
		$('#list-column-milestones').fadeIn(600);
		$('#calendar-milestones').fadeIn(600);
		
		$('.workstream-head[data-id="' + workstreamID + '"]').fadeIn(600);
		
		overview = false;
		
		initDetailUpdateCycle(workstreamID);
		
	}, 600);
	
	setCurrentWorkstream(workstreamID);
};


// vars & functions for workstream detail navigation

var initWorkstreamDetail = function(workstreamID) {
	
	$('#workstream-wrap').show();
	$('#workstream-wrap').animate({
		top: headerH + "px"
	}, 600);
	
	var spinnerTarget = document.getElementById('workstream-wrap');
	var milestoneSpinner = new Spinner(spinnerConfig).spin(spinnerTarget);
	
	setTimeout(function() {
		
		$('.timeline-btn').hide();
		
		var requestURL = 'timeline/workstream/' + workstreamID;
		
		var requestOptions = {
			success: function(data) {
				if (data.status == 'success') {
					
					$('#workstream-wrap').empty();
					$('#workstream-wrap').html(data.content);
					
					$('#index').hide();
					$('#workstream-wrap').css({
						position:	'relative',
						top:		'auto',
						left:		'auto'
					});
					
					$('.timeline-btn').hide();
					$('.workstream-detail-close-btn').show();
				}
			}
		};
		
		ajaxRequest(requestURL, requestOptions);
		
	}, 600);
};

var exitWorkstreamDetail = function() {
	
	$('#workstream-wrap').css({
		position:	'absolute',
		top:		headerH + "px",
		left:		'0px'
	});
	$('#header').show();
	$('#index').show();
	
	$('#workstream-wrap').animate({
		top: "100%"
	}, 600);
	
	setTimeout(function() {
		
		$('#workstream-wrap').hide();
		$('#workstream-wrap').empty();
		
		$('.timeline-btn').hide();
		$('.add-milestone-btn').show();
		
		if (!overview) {
			$('.workstreams-overview-btn').show();
		}
		
	}, 600);
};


// vars & functions for milestone detail navigation

var initMilestoneDetail = function(milestoneID, milestoneColor) {
	
	$('.bg-workstream-color').css('background-color', '#' + milestoneColor);
	$('.fg-workstream-color').css('color', '#' + milestoneColor);
	
	$('#milestone-wrap').show();
	$('#milestone-wrap').animate({
		top: headerH + "px"
	}, 600);
	
	var spinnerTarget = document.getElementById('milestone-wrap');
	var milestoneSpinner = new Spinner(spinnerConfig).spin(spinnerTarget);
	
	setTimeout(function() {
		
		$('.timeline-btn').hide();
		
		var requestURL = 'timeline/milestone/' + milestoneID;
		
		var requestOptions = {
			success: function(data) {
				if (data.status == 'success') {
					
					$('#milestone-wrap').empty();
					$('#milestone-wrap').html(data.content);
					
					$('#index').hide();
					$('#milestone-wrap').css({
						position:	'relative',
						top:		'auto',
						left:		'auto'
					});
					
					$('.delete-milestone-btn').show();
					
					$('.edit-milestone-btn').attr('data-id', milestoneID);
					$('.edit-milestone-btn').show();
					
					$('.close-milestone-btn').show();
				}
			}
		};
		
		ajaxRequest(requestURL, requestOptions);
		
	}, 600);
};

var exitMilestoneDetail = function() {
	
	$('#milestone-wrap').css({
		position:	'absolute',
		top:		headerH + "px",
		left:		'0px'
	});
	$('#header').show();
	$('#index').show();
	
	$('#milestone-wrap').animate({
		top: "100%"
	}, 600);
	
	setTimeout(function() {
		
		$('#milestone-wrap').hide();
		$('#milestone-wrap').empty();
		
		$('.bg-workstream-color').css('background-color', currentWorkstreamColor);
		$('.fg-workstream-color').css('color', currentWorkstreamColor);
		
		$('.timeline-btn').hide();
		$('.add-milestone-btn').show();
		
		if (!overview) {
			$('.workstreams-overview-btn').show();
		}
		
	}, 600);
};


$(document).ready(function() {
	
	// navigation between workstreams overview & milestones elements
	$('.workstream-milestones-btn').on('click', function(event) {
		initWorkstreamMilestones($(this).attr('data-id'));
		event.preventDefault();
	});
	
	$('.workstreams-overview-btn').on('click', function(event) {
		initWorkstreamsOverview();
		event.preventDefault();
	});
	
	// workstream viewing
	$('.workstream-detail-btn').on('click', function(event) {
		initWorkstreamDetail($(this).attr('data-id'));
		event.preventDefault();
		event.stopImmediatePropagation();
	});
	
	$('.workstream-detail-close-btn').on('click', function(event) {
		exitWorkstreamDetail();
		event.preventDefault();
	});
	
	// milestone viewing - disabled
	/*
	$('#index').on('click', '.milestone-detail-btn', function(event) {
		initMilestoneDetail($(this).attr('data-id'), $(this).attr('data-workstream-color'));
		event.preventDefault();
	});
	
	$('.close-milestone-btn').on('click', function(event) {
		exitMilestoneDetail();
		event.preventDefault();
	});
	*/
	
	// kick everything off...
	initOverviewUpdateCycle();
});
