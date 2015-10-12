// touch-facilitated date picker

var monthsArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
var daysArray = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

(function($) {
	
	$.fn.touchDatePicker = function() {

		var timezoneOffset = 0;

		function setDateValue(dateObj, inputEl) {

			if ($(inputEl).hasClass('time')) {
				
                var value =  dateObj.toISOString().slice(0,16).replace(/\D/g,'');
                
                $(inputEl).val(value);

                $(inputEl).parent().find('.hours').text(("0" + dateObj.getUTCHours()).slice(-2));
                $(inputEl).parent().find('.minutes').text(("0" + dateObj.getUTCMinutes()).slice(-2));
                
            } else {
	            
                $(inputEl).val(dateObj.toISOString().slice(0,10));
            }
            
			$(inputEl).parent().find('.day').text(daysArray[dateObj.getUTCDay()] + ' ' + dateObj.getUTCDate());
			$(inputEl).parent().find('.month').text(monthsArray[dateObj.getUTCMonth()]);
			$(inputEl).parent().find('.year').text(dateObj.getUTCFullYear());
		}

		return this.each(function() {
			
			if ($(this).parents('.mobile-date-picker-wrap').length) return this;

			$(this).attr('type', 'hidden');

			$(this).wrap('<div class="mobile-date-picker-wrap"></div>');

			var wrapper = $(this).parent();
            
            hasTime = $(this).hasClass('time');
            
            if (hasTime) {
	            
	            $(this).parents('.mobile-date-picker-wrap').addClass('date-time');
	            
                var dateTime = ['day','month','year','hours','minutes'];
                
                var i = 0;
                
                dateTime.forEach(function(value) {
	                
	                var elClass = 'middle';
	                
	                if (i == 0) elClass = 'left';
	                if (i == (dateTime.length - 1)) elClass = 'right';
	                
                    $(wrapper).append('<div class="'+elClass+'" data-value-type="'+value+'"><div class="button button-top">+</div><div class="value '+value+'"></div><div class="button button-bottom">&ndash;</div></div>');
                    i++;
                });

                var thisDate = ($(this).val()) ? new Date($(this).attr('data-value')) : new Date();
                
            } else {
	            
                $(wrapper).append('<div class="left" data-value-type="day"><div class="button button-top">+</div><div class="value day"></div><div class="button button-bottom">&ndash;</div></div>');
                $(wrapper).append('<div class="middle" data-value-type="month"><div class="button button-top">+</div><div class="value month"></div><div class="button button-bottom">&ndash;</div></div>');
                $(wrapper).append('<div class="right" data-value-type="year"><div class="button button-top">+</div><div class="value year"></div><div class="button button-bottom">&ndash;</div></div>');
                $(wrapper).append('<div class="clear"></div>');

                var thisDate = ($(this).val()) ? new Date($(this).val()) : new Date();
            }

			var that = this;

			var minDateValue = $(this).attr('data-min-date');
			var maxDateValue = $(this).attr('data-max-date');

			var minDateInput = (minDateValue && $(minDateValue).length > 0) ? true : false;
			var maxDateInput = (maxDateValue && $(maxDateValue).length > 0) ? true : false;

			setDateValue(thisDate, this);

			$(wrapper).find('.button').on('click', function() {

                var valueType = $(this).parent().attr('data-value-type');
                var increaseValue = ($(this).hasClass('button-top')) ? 1 : -1;
                var tempDate = new Date(thisDate.getTime());
                var updateDate = true;
                
                switch (valueType) {

                    case 'year':
                        tempDate.setUTCFullYear(parseInt(tempDate.getUTCFullYear()) + increaseValue);
                        break;

                    case 'month':
                        tempDate.setUTCMonth(parseInt(tempDate.getUTCMonth()) + increaseValue);
                        break;

                    case 'hours':
                        tempDate.setUTCHours(parseInt(tempDate.getUTCHours()) + increaseValue);
                        break;

                    case 'minutes':
                        tempDate.setUTCMinutes(parseInt(tempDate.getUTCMinutes()) + increaseValue);
                        break;

					default:
						tempDate.setUTCDate(parseInt(tempDate.getUTCDate()) + increaseValue);
						break;
				}

                //check min/max date and time (hasTime)

                if (hasTime) {
	                
					if (minDateValue) {
                        var minDateValueData = $(minDateValue).val();
                        minDateValueData = minDateValueData.slice(0, 4) + '-' + minDateValueData.slice(4, 6) + '-' + minDateValueData.slice(6, 8) + ' ' + minDateValueData.slice(8, 10) + ':' + minDateValueData.slice(10, 12);
                        var minDate = (minDateInput) ? new Date(minDateValueData) : new Date(minDateValue);
                        if (tempDate.getTime() < minDate.getTime()) {
                            return false;
                        }
                    }

                    if (maxDateValue) {

                        var maxDateValueData = $(maxDateValue).val();
                        maxDateValueData = maxDateValueData.slice(0,4)+'-'+maxDateValueData.slice(4,6) +'-'+ maxDateValueData.slice(6,8)+ ' ' +maxDateValueData.slice(8,10)+':'+maxDateValueData.slice(10,12);

                        var maxDate = (maxDateInput) ? new Date(maxDateValueData) : new Date(maxDateValue);

                        if (tempDate.getTime() > maxDate.getTime()) {
                            return false;
                        }
                    }
                    
                } else {

                    if (minDateValue) {
					
						var minDate = (minDateInput) ? new Date($(minDateValue).val()) : new Date(minDateValue);

						if (tempDate.getTime() < minDate.getTime()) {
							tempDate = new Date(minDate.getTime());
						}
					}

					if (maxDateValue) {
	
						var maxDate = (maxDateInput) ? new Date($(maxDateValue).val()) : new Date(maxDateValue);
	
						if (tempDate.getTime() > maxDate.getTime()) {
							tempDate = new Date(maxDate.getTime());
						}
					}

                }
                
				thisDate = new Date(tempDate.getTime());
				
				setDateValue(thisDate, that);
			});
			
			$(wrapper).show();
		});
	};
	
}(jQuery));

var initTouchDatePickers = function()
{
	$('.mobile-date-picker').touchDatePicker();
}

$(document).ready(function() {
	
	initTouchDatePickers();
});
