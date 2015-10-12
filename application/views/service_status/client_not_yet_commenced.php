<?php date_default_timezone_set($client->time_zone); ?>
<h1><span><?php echo $client->name; ?></span> will be available as of <?php echo date('D j M Y, H:i', $client->commence); ?><br /><span>(GMT <?php echo date('P', $client->commence); ?>)</span>.</h1>
<p>Contact <a href="mailto:<?php echo $client->admin_user->email; ?>"><?php echo $client->admin_user->email; ?></a> with any enquiries regarding this.</p>