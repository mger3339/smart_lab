<ul class="workstream-list">
	<?php foreach ($workstreams as $workstream): ?>
	<li class="workstream-item" data-id="<?php echo $workstream->id; ?>">
		<ul class="milestone-list" data-workstream-id="<?php echo $workstream->id; ?>" data-url="<?php echo site_url('timeline/milestones/add'); ?>/"></ul>
	</li>
	<?php endforeach; ?>
</ul>
<ul class="hidden">
	<li class="milestone-item-template milestone-detail-btn">
		<div class="milestone-postit"></div>
		<h3 class="milestone-title"></h3>
		<div class="milestone-handle"></div>
	</li>
</ul>