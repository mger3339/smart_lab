<?php foreach ($notifications as $notification): ?>
	<div class="section">
		<div class="details">
			<strong class="subject"><?php echo $notification->subject; ?></strong><br>
			<abbr class="timeago" title="<?php echo date('c', $notification->created); ?>"><?php echo date('c', $notification->created); ?></abbr>
			<p class="message"><?php echo $notification->message; ?></p>
		</div>
	</div>
<?php endforeach; ?>