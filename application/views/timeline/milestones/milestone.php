<div id="milestone-detail" data-id="<?php echo $row->id; ?>">
	
	<div id="milestone-workstream">
		<span class="workstream-name"><?php echo $row->workstream->name; ?></span>
	</div>
	<div class="clear"></div>
	
	<div class="control-group">
		<h2><?php echo $row->title; ?></h2>
	</div>
	
	<div class="control-group">
		<p class="milestone-description"><?php echo $row->description; ?></p>
	</div>
	
</div>