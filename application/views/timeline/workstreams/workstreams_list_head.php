<?php foreach ($workstreams as $workstream): ?>
<div class="workstream-head<?php if ($workstream->description): ?> workstream-detail-btn<?php endif; ?>" data-id="<?php echo $workstream->id; ?>" data-name="<?php echo $workstream->name; ?>" data-color="#<?php echo $workstream->color; ?>">
	<h3 style="background-color: #<?php echo $workstream->color; ?>;"><?php echo $workstream->name; ?></h3>
	<?php if ($workstream->description): ?>
	<!--<div class="workstream-item-btn detail-btn" data-id="<?php echo $workstream->id; ?>"><span>i</span></div>-->
	<?php endif; ?>
</div>
<?php endforeach; ?>