<!DOCTYPE html>
<html lang="en">

<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<meta name="robots" content="noindex">
	
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	
	
	
	<?php echo $css_assets; ?> 
	
	
	
	<title><?php echo $page_title; ?></title>
	
</head>

<body>
	
	<?php echo $flash_message_view; ?> 
	
	<?php echo $system_nav_items_view; ?> 
	
	<?php foreach ($pre_wrap_views as $view): ?> 
	<?php echo $view; ?> 
	<?php endforeach; ?> 
	
	<div id="wrap"<?php if ($wrap_classes): ?> class="<?php echo $wrap_classes; ?>"<?php endif; ?>>
		
		<?php foreach ($wrap_views as $view): ?> 
		<?php echo $view; ?> 
		<?php endforeach; ?> 
		
		<div id="push"></div>
	
	</div>
	
	<div id="footer">
		
		<?php foreach ($footer_views as $view): ?> 
		<?php echo $view; ?> 
		<?php endforeach; ?> 
		
	</div>
	
	<?php echo $js_assets; ?> 
	
	<script type="text/javascript">
		
		if (typeof(console) === 'undefined') {
			var console = {};
			console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
		}
		
		var baseURL = '<?php echo base_url(); ?>';
		
		<?php foreach ($js_views as $view): ?> 
		<?php echo $view; ?> 
		<?php endforeach; ?> 
		
	</script>
	
	<script type="text/javascript">
	$(document).ready(function() {
		
		$(document).ajaxSuccess(function(e, xhr, settings) {
			if (xhr.responseText) {
				var obj = $.parseJSON(xhr.responseText);
				if (obj.redirect) {
					var redirectURL = baseURL + obj.redirect.replace(baseURL, '');
					location.replace(redirectURL);
				}
			}
		});

		<?php foreach ($domready_js_views as $view): ?> 
		<?php echo $view; ?> 
		<?php endforeach; ?> 
		
	});
	</script>
	
</body>

</html>