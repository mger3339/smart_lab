// T Map App data update functionality

// vars & funcitons for DOM element manipulation based on data feed

var updateOverviewMilestoneElements = function(workstreamID, milestonesData) {
	
	var updatedDOM = false;
	
	// build array of valid milestone IDs
	var milestoneIDs = Array();
	$.each(milestonesData, function(i, v) {
		milestoneIDs.push(v.id);
	});
	
	// delete invalid milestones
	var invalidMilestoneIDs = Array();
	$('.milestone-item[data-workstream-id="' + workstreamID + '"]').each(function(i, el) {
		
		var elDataID = $(el).attr('data-id');
		
		if (milestoneIDs.indexOf(elDataID) < 0) {
			$(el).fadeOut(600, function() {
				$(el).remove();
			});
			updatedDOM = true;
			invalidMilestoneIDs.push(elDataID);
		}
	});
	
	// build array of existing valid milestone IDs
	var currentMilestoneIDs = Array();
	$('#calendar-workstreams').find('.milestone-item').each(function(i, el) {
		
		var elDataID = $(el).attr('data-id');
		
		currentMilestoneIDs.push(elDataID);
	});
	
	
	// add new valid / update milestones
	$.each(milestonesData, function(i, v) {
		
		if (currentMilestoneIDs.indexOf(v.id) < 0) {
			
			createOverviewMilestoneElements(v);
			updatedDOM = true;
			
		} else {
			
			var elDataModified = $('#calendar-workstreams').find('.milestone-item[data-id="' + v.id + '"]').attr('data-modified');
			
			if (elDataModified != v.modified) {
				
				modifyOverviewMilestoneElements(v);
				updatedDOM = true;
			}
		}
		
	});
	
	// sort milestones
	if (updatedDOM) {
		sortMilestoneElements();
	}
};

var updateDetailMilestoneElements = function(milestonesData) {
	
	var updatedDOM = false;
	
	// build array of valid milestone IDs
	var milestoneIDs = Array();
	$.each(milestonesData, function(i, v) {
		milestoneIDs.push(v.id);
	});
	
	// delete invalid milestones
	var invalidMilestoneIDs = Array();
	$('.detail-milestone-item').each(function(i, el) {
		
		var elDataID = $(el).attr('data-id');
		
		if (milestoneIDs.indexOf(elDataID) < 0) {
			$(el).slideUp(600, function() {
				$(el).remove();
			});
			updatedDOM = true;
			invalidMilestoneIDs.push(elDataID);
		}
	});
	
	// build array of existing valid milestone IDs
	var currentMilestoneIDs = Array();
	$('#list-column-milestones').find('.milestone-item').each(function(i, el) {
		
		var elDataID = $(el).attr('data-id');
		
		currentMilestoneIDs.push(elDataID);
	});
	
	// add new valid / update milestones
	$.each(milestonesData, function(i, v) {
		
		if (currentMilestoneIDs.indexOf(v.id) < 0) {
			
			createDetailMilestoneElements(v);
			updatedDOM = true;
			
		} else {
			
			var elDataModified = $('#list-column-milestones').find('.milestone-item[data-id="' + v.id + '"]').attr('data-modified');
			
			if (elDataModified != v.modified) {
				
				modifyDetailMilestoneElements(v);
				updatedDOM = true;
			}
		}
		
	});
	
	// sort milestones
	if (updatedDOM) {
		
		setTimeout(function() {
			
			var milestoneH = $('#list-column-milestones').find('.milestone-item').outerHeight();
			var totalH = $('#list-column-milestones').find('.milestone-item').length * milestoneH;
			
			$('#list-column-milestones').height(totalH);
			$('#calendar-milestones').height(totalH);
			
			var columnItemW = $('#list-column-milestones').find('.milestone-item').width();
			var calendarItemW = $('#calendar-milestones').find('.milestone-item').width();
			
			$('#list-column-milestones').find('.milestone-item').each(function(i, el) {
				var currentTop = i * milestoneH;
				$(this).css({
					position: 'absolute',
					top: currentTop + 'px',
					width: columnItemW
				});
			});
			
			$('#calendar-milestones').find('.milestone-item').each(function(i, el) {
				var currentTop = i * milestoneH;
				var currentLeft = $(this).position().left;
				$(this).css({
					position: 'absolute',
					top: currentTop + 'px',
					left: currentLeft + 'px',
					width: calendarItemW
				});
			});
			
			sortMilestoneElements();
			
			$('#list-column-milestones').find('.milestone-item').each(function(i, el) {
				var currentTop = $(this).position().top;
				var newTop = i * milestoneH;
				if (newTop != currentTop) {
					$(this).animate({
						top: newTop + 'px'
					}, 600);
				}
			});
			
			$('#calendar-milestones').find('.milestone-item').each(function(i, el) {
				var currentTop = $(this).position().top;
				var newTop = i * milestoneH;
				if (newTop != currentTop) {
					$(this).animate({
						top: newTop + 'px'
					}, 600);
				}
			});
			
		}, 600);
		
		setTimeout(function() {
			
			$('#list-column-milestones').css({
				height: 'auto'
			});
			
			$('#calendar-milestones').css({
				height: 'auto'
			});
			
			$('#list-column-milestones').find('.milestone-item').css({
				position: 'relative',
				top: 'auto',
				width: 'auto'
			});
			
			$('#calendar-milestones').find('.milestone-item').css({
				position: 'relative',
				top: 'auto',
				left: 'auto',
				width: 'auto'
			});
			
		}, 1500);
	}
};

var sortMilestoneElements = function() {
	$('.milestone-list').each(function(i, el) {
		$(this).find('.milestone-item').sort(function(a, b) {
			var itemA = parseInt( $(a).attr('data-start') );
			var itemB = parseInt( $(b).attr('data-start') );
			return (itemA < itemB) ? -1 : (itemA > itemB) ? 1 : 0;
		}).appendTo(this);
	});
};

var createOverviewMilestoneElements = function(milestoneData) {
	
	var workstreamItemEl = $('#calendar-workstreams').find('.milestone-item-template').clone();
	var workstreamListEl = $('.milestone-list[data-workstream-id="' + milestoneData.workstream.id + '"]');
	
	$(workstreamItemEl).removeClass('milestone-item-template');
	$(workstreamItemEl).addClass('milestone-item');
	$(workstreamItemEl).attr('data-id', milestoneData.id);
	$(workstreamItemEl).attr('data-workstream-id', milestoneData.workstream.id);
	$(workstreamItemEl).attr('data-workstream-color', milestoneData.workstream.color);
	$(workstreamItemEl).attr('data-start', milestoneData.start_offset);
	$(workstreamItemEl).attr('data-end', milestoneData.end_offset);
	$(workstreamItemEl).attr('data-modified', milestoneData.modified);
	
	var elTop = (milestoneData.y_position) ? milestoneData.y_position : Math.floor(Math.random() * 6) * 6 + 6;
	
	$(workstreamItemEl).css({
		top:				elTop + 'px',
		left:				(milestoneData.start_offset * calendarScale) + 'px',
		backgroundColor:	'#' + milestoneData.workstream.color
	});
	
	$(workstreamItemEl).hide();
	$(workstreamItemEl).appendTo(workstreamListEl);
	
	if ($(workstreamItemEl).prev().length) {
		var prevElTop = parseInt($(workstreamItemEl).prev().css('top'));
		if (prevElTop == elTop) {
			if (elTop >= 75) {
				elTop-= calendarScale;
			} else {
				elTop+= calendarScale;
			}
			$(workstreamItemEl).css('top', elTop + 'px');
		}
	}
	
	$(workstreamItemEl).find('.milestone-postit').css({
		width:				((milestoneData.end_offset - milestoneData.start_offset + 1) * calendarScale) + 'px',
		backgroundColor:	'#' + milestoneData.workstream.color
	});
	
	$(workstreamItemEl).draggable({
		containment: 'parent',
		grid: [5, 1],
		handle: '.milestone-handle',
		scroll: true,
		scrollSensitivity: 100,
		start: function() {
			
			refreshDataActive = false;
		},
		stop: function() {
			
			refreshDataActive = true;
			
			var currentDuration = parseInt($(this).attr('data-end')) - parseInt($(this).attr('data-start'));
			
			var startOffset = parseInt($(this).css('left')) / calendarScale + 1;
			$(this).attr('data-start', startOffset);
			
			var endOffset = startOffset + currentDuration;
			$(this).attr('data-end', endOffset);
			
			updateMilestoneStartEnd(this, true);
		}
	});
	
	$(workstreamItemEl).find('.milestone-title').text(milestoneData.title);
	
	$(workstreamItemEl).fadeIn();
};

var createDetailMilestoneElements = function(milestoneData) {
	
	var columnItemEl = $('#list-column-milestones').find('.milestone-item-template').clone();
	
	$(columnItemEl).removeClass('milestone-item-template');
	$(columnItemEl).addClass('milestone-item');
	$(columnItemEl).addClass('detail-milestone-item');
	$(columnItemEl).attr('data-id', milestoneData.id);
	$(columnItemEl).attr('data-workstream-id', milestoneData.workstream.id);
	$(columnItemEl).attr('data-workstream-color', milestoneData.workstream.color);
	$(columnItemEl).attr('data-start', milestoneData.start_offset);
	$(columnItemEl).attr('data-end', milestoneData.end_offset);
	$(columnItemEl).attr('data-modified', milestoneData.modified);
	$(columnItemEl).find('h3').text(milestoneData.title);
	$(columnItemEl).find('h3').css({
		borderColor:	'#' + milestoneData.workstream.color
	});
	$(columnItemEl).find('.milestone-item-btn').attr('data-id', milestoneData.id);
	$(columnItemEl).find('.milestone-item-btn').css({
		borderColor:	'#' + milestoneData.workstream.color
	});
	
	if (milestoneData.description.length > 0) {
		$(columnItemEl).find('.detail-btn').show();
	}
	
	$(columnItemEl).hide();
	$(columnItemEl).appendTo('#list-column-milestones > .milestone-list').slideDown();
	
	var calendarItemEl = $('#calendar-milestones').find('.milestone-item-template').clone();
	
	$(calendarItemEl).removeClass('milestone-item-template');
	$(calendarItemEl).addClass('milestone-item');
	$(calendarItemEl).addClass('detail-milestone-item');
	$(calendarItemEl).attr('data-id', milestoneData.id);
	$(calendarItemEl).attr('data-workstream-id', milestoneData.workstream.id);
	$(calendarItemEl).attr('data-start', milestoneData.start_offset);
	
	var milestonePostitEl = $(calendarItemEl).find('.milestone-postit');
	
	$(milestonePostitEl).attr('data-id', milestoneData.id);
	$(milestonePostitEl).attr('data-start', milestoneData.start_offset);
	$(milestonePostitEl).attr('data-end', milestoneData.end_offset);
	$(milestonePostitEl).attr('data-workstream-id', milestoneData.workstream.id);
	$(milestonePostitEl).attr('data-workstream-color', milestoneData.workstream.color);
	$(milestonePostitEl).css({
		left:	(milestoneData.start_offset * calendarScale) + 'px',
		width:	((milestoneData.end_offset - milestoneData.start_offset + 1) * calendarScale) + 'px'
	});
	
	$(milestonePostitEl).draggable({
		axis: 'x',
		containment: 'parent',
		grid: [calendarScale, 5],
		scroll: true,
		scrollSensitivity: 100,
		start: function() {
			
			refreshDataActive = false;
		},
		stop: function() {
			
			refreshDataActive = true;
			
			var currentDuration = parseInt($(this).attr('data-end')) - parseInt($(this).attr('data-start'));
			
			var startOffset = parseInt($(this).css('left')) / calendarScale + 1;
			$(this).attr('data-start', startOffset);
			
			var endOffset = startOffset + currentDuration;
			$(this).attr('data-end', endOffset);
			
			updateMilestoneStartEnd(this, false);
		}
	});
	
	$(milestonePostitEl).resizable({
		containment: 'parent',
		grid: [5, 5],
		handles: 'e',
		minWidth: (5),
		start: function() {
			
			refreshDataActive = false;
		},
		stop: function() {
			
			refreshDataActive = true;
			
			var startOffset = parseInt($(this).css('left')) / calendarScale;
			$(this).attr('data-start', startOffset);
			
			var endOffset = startOffset + ($(this).width() / calendarScale);
			$(this).attr('data-end', endOffset);
			
			updateMilestoneStartEnd(this, false);
		}
	});
	
	var postitEl = $(calendarItemEl).find('.postit');
	
	$(postitEl).attr('data-id', milestoneData.id);
	$(postitEl).attr('data-workstream-id', milestoneData.workstream.id);
	$(postitEl).attr('data-workstream-color', milestoneData.workstream.color);
	$(postitEl).css({
		backgroundColor:	'#' + milestoneData.workstream.color
	});
	
	$(calendarItemEl).hide();
	$(calendarItemEl).appendTo('#calendar-milestones > .milestone-list').slideDown();
};

var modifyOverviewMilestoneElements = function(milestoneData) {
	
	var workstreamItemEl = $('#calendar-workstreams').find('.milestone-item[data-id="' + milestoneData.id + '"]');
	
	$(workstreamItemEl).attr('data-end', milestoneData.end_offset);
	$(workstreamItemEl).attr('data-modified', milestoneData.modified);
	
	var elTop = parseInt($(workstreamItemEl).css('top'));
	var elLeft = parseInt($(workstreamItemEl).css('left'));
	var elWidth = $(workstreamItemEl).width();
	
	var newOffset = ((milestoneData.start_offset) * calendarScale);
	var newWidth = ((milestoneData.end_offset - milestoneData.start_offset + 1) * calendarScale);
	
	if (elLeft != newOffset) {
		
		if ($(workstreamItemEl).prev().length) {
			var prevElTop = parseInt($(workstreamItemEl).prev().css('top'));
			if (prevElTop == elTop) {
				if (elTop >= 40) {
					elTop-= 5;
				} else {
					elTop+= 5;
				}
			}
		}
		
		$(workstreamItemEl).attr('data-start', milestoneData.start_offset);
		
		setTimeout(function() {
			$(workstreamItemEl).animate({
				top: elTop + 'px',
				left: newOffset + 'px'
			}, 600);
		}, 600);
	}
	
	if (elWidth != newWidth) {
		
		setTimeout(function() {
			$(workstreamItemEl).find('.milestone-postit').animate({
				width: newWidth
			}, 600);
		}, 600);
	}
	
	$(workstreamItemEl).find('.milestone-title').text(milestoneData.title);
};

var modifyDetailMilestoneElements = function(milestoneData) {
	
	var columnItemEl = $('#list-column-milestones').find('.milestone-item[data-id="' + milestoneData.id + '"]');
	
	$(columnItemEl).attr('data-start', milestoneData.start_offset);
	$(columnItemEl).attr('data-end', milestoneData.end_offset);
	$(columnItemEl).attr('data-modified', milestoneData.modified);
	$(columnItemEl).find('h3').text(milestoneData.title);
	
	if (milestoneData.description.length > 0) {
		$(columnItemEl).find('.detail-btn').show();
	} else {
		$(columnItemEl).find('.detail-btn').hide();
	}
	
	var calendarItemEl = $('#calendar-milestones').find('.milestone-item[data-id="' + milestoneData.id + '"]');
	$(calendarItemEl).attr('data-start', milestoneData.start_offset);
	$(calendarItemEl).attr('data-end', milestoneData.end_offset);
	$(calendarItemEl).find('.milestone-postit').css({
		left:	(milestoneData.start_offset * calendarScale) + 'px',
		width:	((milestoneData.end_offset - milestoneData.start_offset + 1) * calendarScale) + 'px'
	});
	
	var milestonePostitEl = $(calendarItemEl).find('.milestone-postit');
	$(milestonePostitEl).attr('data-start', milestoneData.start_offset);
	$(milestonePostitEl).attr('data-end', milestoneData.end_offset);
};


// vars & functions for data refresh

var workstreamUpdateIndex = 0;
var workstreamUpdate = 0;
var milestoneUpdate = 0;

var refreshDataActive = true;

var milestonesRequestURL = 'timeline/workstream/{workstream_id}/milestones';

var initOverviewUpdateCycle = function() {
	
	stopOverviewUpdateCycle();
	stopDetailUpdateCycle();
	
	workstreamUpdateIndex = 0;
	
	updateOverviewMilestones();
	
	workstreamUpdate = setInterval(function() {
		updateOverviewMilestones();
	}, 5000);
};

var stopOverviewUpdateCycle = function() {
	clearInterval(workstreamUpdate);
	workstreamUpdate = 0;
};

var initDetailUpdateCycle = function(workstreamID) {
	
	stopOverviewUpdateCycle();
	stopDetailUpdateCycle();
	
	currentDetailMilestoneIDs = Array();
	
	updateDetailMilestones(workstreamID);
	
	milestoneUpdate = setInterval(function() {
		updateDetailMilestones(workstreamID);
	}, 5000);
};

var stopDetailUpdateCycle = function() {
	clearInterval(milestoneUpdate);
	milestoneUpdate = 0;
};

var updateOverviewMilestones = function() {
	
	if (!refreshDataActive) return;
	
	if (workstreamUpdateIndex < $('#list-column-workstreams').find('.workstream-item').length) {
		
		var workstreamEl = $('#list-column-workstreams').find('.workstream-item').get(workstreamUpdateIndex);
		var workstreamID = $(workstreamEl).attr('data-id');
		
		var requestURL = milestonesRequestURL.replace('{workstream_id}', workstreamID);
		
		workstreamUpdateIndex++;
		
		var requestOptions = {
			success: function(data) {
				if (data.status == 'success') {
					
					updateOverviewMilestoneElements(workstreamID, data.data);
					updateOverviewMilestones();
				}
			}
		};
		
		ajaxRequest(requestURL, requestOptions);
		
	} else {
		
		workstreamUpdateIndex = 0;
	}
};

var updateDetailMilestones = function(workstreamID) {
	
	if (!refreshDataActive) return;
	
	var requestURL = milestonesRequestURL.replace('{workstream_id}', workstreamID);
	
	var requestOptions = {
		success: function(data) {
			if (data.status == 'success') {
				
				updateDetailMilestoneElements(data.data);
			}
		}
	};
	
	ajaxRequest(requestURL, requestOptions);
};

