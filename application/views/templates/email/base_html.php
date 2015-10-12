<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title><?php echo $subject; ?></title>
</head>
<body style="background-color: #ededed;">
	<div style="background-color: #ededed; width: auto; margin: 0; padding: 20px; font:12px/17px Helvetica, Arial, sans-serif; color: #666666;">
		<?php if (is_object($client)): ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="left" style="padding-left:2px;">
					<strong><?php echo $client->name; ?></strong>
					<span> Notification</span>
				</td>
				<td align="right" style="padding-bottom:20px;">
					<!-- client logo here -->
				</td>
			</tr>
		</table>
		<?php endif; ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="left" style="background-color: #ffffff; padding: 20px; border: #cccccc solid 1px;">

					<?php echo $message; ?>

    			</td>
			</tr>
			<tr>
				<td align="left">
					<p style="padding-left:2px;font-size:9px;color:#666666;">
						<?php if (is_object($client)): ?>
						<span><?php echo $client->name; ?></span>
						<span> powered by </span>
						<?php endif; ?>
						<span><?php echo APPLICATION_NAME; ?></span>
					</p>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>