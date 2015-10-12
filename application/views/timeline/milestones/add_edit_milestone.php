<?php echo form_open($form_action, array('id' => 'milestone-add-edit')); ?>
	
	<?php if ( ! $row->timeline_workstream_id ) $row->timeline_workstream_id = $workstreams[0]->id; ?>
	
	<div id="milestone-workstream-chooser">
		<div class="milestone-workstreams">
			<?php foreach ($workstreams as $workstream): ?>
			<div class="milestone-workstream-choice">
				<label
					for="workstream-id-input-<?php echo $workstream->id; ?>"\
					class="workstream-id-input"
					style="background-color: #<?php echo $workstream->color; ?>;"
					data-id="<?php echo $workstream->id; ?>"
					data-name="<?php echo $workstream->name; ?>"
					data-color="#<?php echo $workstream->color; ?>"
					>
					<input
						id="workstream-id-input-<?php echo $workstream->id; ?>"
						type="radio"
						name="timeline_workstream_id"
						value="<?php echo $workstream->id; ?>"
						<?php if ($workstream->id == $row->timeline_workstream_id): ?>
						checked="checked"
						<?php endif; ?>
						/>
					<span><?php echo $workstream->name; ?></span>
					<span class="clear"></span>
				</label>
			</div>
			<?php endforeach; ?>
			<div class="clear"></div>
		</div>
	</div>
	
	<div class="top-column top-column-first control-group">
		<label for="milestone-start-date">Start date</label>
		<input
			id="milestone-start-date"
			class="mobile-date-picker"
			type="text"
			name="start_date"
			value="<?php echo date('Y-m-d', strtotime($row->start_date)); ?>"
			data-min-date="<?php echo $timeline_settings->calendar_start; ?>"
			data-max-date="#milestone-end-date"
		/>
		<div class="clear"></div>
	</div>
	
	<div class="top-column control-group">
		<label for="milestone-end-date">End date</label>
		<input
			id="milestone-end-date"
			class="mobile-date-picker"
			type="text"
			name="end_date"
			value="<?php echo date('Y-m-d', strtotime($row->end_date)); ?>"
			data-min-date="#milestone-start-date"
			data-max-date="<?php echo $timeline_settings->calendar_end; ?>"
		/>
		<div class="clear"></div>
	</div>
	
	<div class="top-column top-column-workstream control-group">
		<label>Workstream</label>
		<div id="milestone-workstream"><span class="workstream-name"><?php echo $row->workstream->name; ?></span></div>
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
	
	<div class="control-group">
		<?php echo form_error('title'); ?>
		<label>Title</label>
		<textarea class="milestone-title" name="title" cols="30" rows="1" placeholder="Milestone title"><?php echo set_value('title', $row->title); ?></textarea>
	</div>
	
	<div class="control-group">
		<label>Description</label>
		<textarea class="milestone-description" name="description" cols="30" rows="1" placeholder="Description"><?php echo set_value('description', $row->description); ?></textarea>
	</div>
	
<?php echo form_close(); ?>

<?php if ($action === 'edit'): ?>
<?php echo form_open("timeline/milestones/delete/{$row->id}", array('id' => 'milestone-delete')); ?>
<input id="milestone-id" type="hidden" name="milestone_id" value="<?php echo $row->id; ?>" />
<?php echo form_close(); ?>
<?php endif; ?>