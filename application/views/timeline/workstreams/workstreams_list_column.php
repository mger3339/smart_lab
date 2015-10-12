<ul class="workstream-list">
	<?php foreach ($workstreams as $workstream): ?>
	<li class="workstream-item workstream-milestones-btn" data-id="<?php echo $workstream->id; ?>" data-color="<?php echo $workstream->color; ?>">
		<h3 style="background-color: #<?php echo $workstream->color; ?>;"><?php echo $workstream->name; ?></h3>
		<?php if ($workstream->description): ?>
		<div class="workstream-item-btn detail-btn workstream-detail-btn" data-id="<?php echo $workstream->id; ?>" style="border-color: #<?php echo $workstream->color; ?>;"><span>i</span></div>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>
<div class="clear"></div>