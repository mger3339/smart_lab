// T Map App milestone CRUD UI functionality

// vars & functionality for adding & editing milestones

var currentWorkstreamID = 0;
var currentWorkstreamName = '';
var currentWorkstreamColor = '#fff';
var newMilestoneID = 0;

var newMilestoneX = 0;

var updateDataActive = false;

var setCurrentWorkstream = function(workstreamID) {
	
	var workstreamEl = $('.workstream-head[data-id="' + workstreamID + '"]');
	
	currentWorkstreamID = $(workstreamEl).attr('data-id');
	currentWorkstreamName = $(workstreamEl).attr('data-name');
	currentWorkstreamColor = $(workstreamEl).attr('data-color');
	
	$('.workstream-id').attr('data-workstream-id', currentWorkstreamID);
	
	$('.workstream-name').text(currentWorkstreamName);
	
	 $('.bg-workstream-color').css('background-color', currentWorkstreamColor);
	$('.fg-workstream-color').css('color', currentWorkstreamColor);
};

var initAddEditMilestoneForm = function(formContent) {
	
	$('#milestone-wrap').empty();
	$('#milestone-wrap').html(formContent);
	
	workstreamID = $("input[type='radio'][name='timeline_workstream_id']:checked").val();
	
	$('#index').hide();
	$('#milestone-wrap').css({
		position:	'relative',
		top:		'auto',
		left:		'auto'
	});
	
	$('#milestone-wrap').find('textarea').elastic();
	
	if (newMilestoneX > 0) {
		
		var daysOffset = Math.ceil(newMilestoneX / calendarScale);
		
		var calendarStartDate = new Date(calendarStart);
		var calendarEndDate = new Date(calendarStart);
		
		calendarStartDate.setDate(parseInt(calendarStartDate.getDate()) + daysOffset);
		calendarEndDate.setDate(parseInt(calendarEndDate.getDate()) + daysOffset + 6);
		
		$('#milestone-start-date').val(calendarStartDate.toISOString().slice(0,10));
		$('#milestone-end-date').val(calendarEndDate.toISOString().slice(0,10));
	}
	
	initTouchDatePickers();
};

var initAddMilestone = function(requestURL) {
	
	refreshDataActive = false;
	
	$('.bg-workstream-color').css('background-color', currentWorkstreamColor);
	$('.fg-workstream-color').css('color', currentWorkstreamColor);
	
	$('#milestone-wrap').show();
	$('#milestone-wrap').animate({
		top: headerH + "px"
	}, 600);
	
	var spinnerTarget = document.getElementById('milestone-wrap');
	var milestoneSpinner = new Spinner(spinnerConfig).spin(spinnerTarget);
	
	setTimeout(function() {
		
		$('.timeline-btn').hide();
		$('.add-milestone-confirm-btn').show();
		$('.add-milestone-cancel-btn').show();
		
		var requestOptions = {
			success: function(data) {
				if (data.status == 'success') {
					initAddEditMilestoneForm(data.content);
				}
			}
		};
		
		ajaxRequest(requestURL, requestOptions);
		
	}, 600);
};

var confirmAddMilestone = function() {
	
	if (updateDataActive) return;
	
	if ($('#milestone-wrap').find('#milestone-add-edit').length < 1) return;
	
	var requestURL = $('#milestone-add-edit').attr('action');
	
	var requestOptions = {
		type: 'POST',
		data: $('#milestone-add-edit').serialize(),
		success: function(data) {
			
			if (data.status == 'success') {
				
				newMilestoneID = data.insert_id;
				
				focusNewMilestone();
				
			} else if (data.status == 'error') {
				
				initAddEditMilestoneForm(data.content);
			}
			
			updateDataActive = false;
		}
	};
	
	ajaxRequest(requestURL, requestOptions);
	
	updateDataActive = true;
};

var focusNewMilestone = function() {
	
	$('#list-column-workstreams').hide();
	$('#calendar-workstreams').hide();
	
	$('#list-column-milestones').parent().prepend($('#list-column-milestones'));
	$('#calendar-milestones').parent().prepend($('#calendar-milestones'));
	
	$('#list-column-milestones').show();
	$('#calendar-milestones').show();
	
	$('.workstream-head').hide();
	$('.workstream-head[data-id="' + currentWorkstreamID + '"]').show();
	
	overview = false;
	
	refreshDataActive = true;
	
	initDetailUpdateCycle(currentWorkstreamID);
	
	exitAddEditMilestone();
};

var cancelAddMilestone = function() {
	
	exitAddEditMilestone();
};

var exitAddEditMilestone = function() {
	
	newMilestoneX = 0;
	
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
	
	$('.timeline-btn').hide();
	$('.add-milestone-btn').show();
	
	if (!overview) {
		$('.workstreams-overview-btn').show();
	}
	
	setTimeout(function() {
		
		$('#milestone-wrap').hide();
		$('#milestone-wrap').empty();
		
	}, 600);
	
	refreshDataActive = true;
};

var initEditMilestoneForm = function(milestoneID, milestoneColor) {
	
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
		
		var requestURL = 'timeline/milestones/edit/' + milestoneID;
		
		var requestOptions = {
			success: function(data) {
				if (data.status == 'success') {
					
					initAddEditMilestoneForm(data.content);
					
					$('#index').hide();
					$('#milestone-wrap').css({
						position:	'relative',
						top:		'auto',
						left:		'auto'
					});
					
					$('.delete-milestone-btn').show();
					
					$('.edit-milestone-confirm-btn').show();
					
					$('.close-milestone-btn').show();
					
					updateDataActive = false;
				}
			}
		};
		
		ajaxRequest(requestURL, requestOptions);
		
	}, 600);
};

var confirmEditMilestone = function() {
	
	if (updateDataActive) return;
	
	if ($('#milestone-wrap').find('#milestone-add-edit').length < 1) return;
	
	var requestURL = $('#milestone-add-edit').attr('action');
	
	var requestOptions = {
		type: 'POST',
		data: $('#milestone-add-edit').serialize(),
		success: function(data) {
			
			if (data.status == 'success') {
				
				$('.timeline-btn').hide();
				$('.add-milestone-btn').show();
				
				if (!overview) {
					$('.workstreams-overview-btn').show();
				}
				
				exitAddEditMilestone();
				
			} else if (data.status == 'error') {
				
				initAddEditMilestoneForm(data.content);
			}
			
			updateDataActive = false;
		}
	};
	
	ajaxRequest(requestURL, requestOptions);
	
	updateDataActive = true;
}

var cancelEditMilestone = function() {
	
	exitAddEditMilestone();
};


// vars & functionality for editing milestone start & end dates

var updateMilestoneStartEnd = function(postitEl, updateYPosition) {
	
	var requestURL = 'timeline/milestones/edit-start-end/' + $(postitEl).attr('data-id');
		requestURL+= '/' + parseInt($(postitEl).attr('data-start'));
		requestURL+= '/' + parseInt($(postitEl).attr('data-end'));
	
	if (updateYPosition) {
		requestURL+= '/' + parseInt($(postitEl).position().top);
	}
	
	var requestOptions = {
		success: function(data) { }
	};
	
	ajaxRequest(requestURL, requestOptions);
};


// functionality for deleting milestones

var initDeleteMilestone = function() {
	
	var confirmDelete = confirm("Are you sure you would like to delete this milestone?\n");
		
	if (confirmDelete) {
		
		confirmDeleteMilestone();
	}
};

var confirmDeleteMilestone = function() {
	
	if (updateDataActive) return;
	
	var milestoneID = $('#milestone-id').val();
	
	var requestURL = $('#milestone-delete').attr('action');
	
	var requestOptions = {
		type: 'POST',
		data: $('#milestone-delete').serialize(),
		success: function(data) {
			
			if (data.status == 'success') {
				exitAddEditMilestone();
				$('.milestone-item[data-id="' + milestoneID + '"]').remove();
			}
			
			updateDataActive = false;
		}
	};
	
	ajaxRequest(requestURL, requestOptions);
	
	updateDataActive = true;
};


$(document).ready(function() {
	
	// add, add-confirm and cancel-add milestone
	$('.add-milestone-btn').show();
	$('.add-milestone-btn').on('click', function(event) {
		var requestURL = $(this).attr('data-url') + $(this).attr('data-workstream-id');
		initAddMilestone(requestURL);
		event.preventDefault();
	});
	
	$('#calendar-workstreams').on('doubletap', '.milestone-list', function(event, eventData) {
		
		if ($(event.target).hasClass('milestone-list')) {
			
			var parentScrollLeft = $(this).parents('#calendar-body-wrap').scrollLeft();
			var parentPaddingLeft = parseInt($(this).parents('#calendar-workstreams').css('padding-left'));
			newMilestoneX = parentScrollLeft + eventData.firstTap.position.x - parentPaddingLeft;
			
			setCurrentWorkstream($(this).attr('data-workstream-id'));
			
			var requestURL = $(this).attr('data-url') + $(this).attr('data-workstream-id');
			
			initAddMilestone(requestURL);
		}
	});
	
	$('.add-milestone-confirm-btn').on('click', function(event) {
		confirmAddMilestone();
		event.preventDefault();
	});
	
	$('.add-milestone-cancel-btn').on('click', function(event) {
		cancelAddMilestone();
		event.preventDefault();
	});
	
	// edit, edit-confirm and cancel-edit milestone
	/*
	$('.edit-milestone-btn').on('click', function(event) {
		initEditMilestoneForm();
		event.preventDefault();
	});
	*/
	
	$('#index').on('click', '.milestone-detail-btn', function(event) {
		initEditMilestoneForm($(this).attr('data-id'), $(this).attr('data-workstream-color'));
		event.preventDefault();
	});
	
	$('.edit-milestone-confirm-btn').on('click', function(event) {
		confirmEditMilestone();
		event.preventDefault();
	});
	
	/*
	$('.edit-milestone-cancel-btn').on('click', function(event) {
		cancelEditMilestone();
		event.preventDefault();
	});
	*/
	
	$('.close-milestone-btn').on('click', function(event) {
		exitMilestoneDetail();
		event.preventDefault();
	});
	
	// milestone form workstream chooser
	$('#milestone-wrap').on('click', '#milestone-workstream', function(event) {
		$('#milestone-workstream-chooser').slideDown(300);
		event.preventDefault();
	});
	
	$('#milestone-wrap').on('click', '.workstream-id-input', function(event) {
		$('#milestone-workstream-chooser').slideUp(300);
		setCurrentWorkstream($(this).attr('data-id'));
	});
	
	// delete milestone
	$('.delete-milestone-btn').on('click', function(event) {
		initDeleteMilestone();
		event.preventDefault();
	});
	
	// set the default workstream data
	var defaultWorkstreamEl = $('#list-column-workstreams').find('.workstream-item').first();
	currentWorkstreamID = $(defaultWorkstreamEl).attr('data-id');
	currentWorkstreamColor = '#' + $(defaultWorkstreamEl).attr('data-color');
});
