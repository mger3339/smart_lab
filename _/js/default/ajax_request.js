// global ajax request
var ajaxRequest = function(requestURL, requestOptions) {
	
	requestURL = requestURL.replace(baseURL, '');
	
	var timeNow = +new Date;
	var requestSettings = {
		url: baseURL + requestURL + '?' + timeNow,
		dataType: 'json',
		timeout: 120000,
		complete: function(jqXHR, status) {
			if (status == 'parsererror' || status == 'error') {
				console.log(jqXHR.responseText);
			}
			if (jqXHR.responseText == 'invalid_session') {
				location.replace(baseURL);
			}
		}
	};
	$.extend(requestSettings, requestOptions);
	return $.ajax(requestSettings);
	
}
