<div class="admin-content">
	
	<h2>Timeline Calendar</h2>
	
	<ul class="data-rows-list">
		
		<li class="data-row">
			<?php echo form_open('timeline/admin/calendar'); ?>
			
			<div class="row">
				
				<div class="control-group">
					<label for="calendar-start">Calendar start</label>
					<input
						id="calendar-start"
						class="date-picker"
						type="text"
						name="calendar_start"
						value="<?php echo strftime("%e %B %Y", strtotime($timeline_settings->calendar_start)); ?>"
						data-date="<?php echo strtotime($timeline_settings->calendar_start); ?>"
						data-year-start="<?php echo intval(strftime("%Y", strtotime($timeline_settings->calendar_start))) - 30; ?>"
						data-year-end="<?php echo intval(strftime("%Y", strtotime($timeline_settings->calendar_start))) + 50; ?>"
					/>
				</div>
				
				<div class="control-group">
					<label for="calendar-end">Calendar end</label>
					<input
						id="calendar-end"
						class="date-picker"
						type="text"
						name="calendar_end"
						value="<?php echo strftime("%e %B %Y", strtotime($timeline_settings->calendar_end)); ?>"
						data-date="<?php echo strtotime($timeline_settings->calendar_end); ?>"
						data-year-start="<?php echo intval(strftime("%Y", strtotime($timeline_settings->calendar_start))) - 30; ?>"
						data-year-end="<?php echo intval(strftime("%Y", strtotime($timeline_settings->calendar_start))) + 50; ?>"
					/>
				</div>
				
				<div class="control-group">
					<label for="calendar-division">Format</label>
					<?php echo form_dropdown('calendar_division', $calendar_division_options, $timeline_settings->calendar_division); ?>
				</div>
				
				<div class="clear"></div>
				
				<div class="form-btns">
					<button type="submit" name="action" value="update">Update</button>
					<div class="clear"></div>
				</div>
				
			</div>
			
			<?php echo form_close(); ?>
		</li>
		
	</ul>
	
	<h2>Timeline Workstreams</h2>
	
	<?php if (count($workstreams) > 0): ?>
	<ul class="data-rows-list">
		
		<?php foreach ($workstreams as $row): ?>
		<li class="data-row">
			<?php echo form_open(); ?>
				
				<div class="row">
					
					<div class="control-group control-group-large">
						<label for="ws-<?php echo $row->id; ?>-name">Name</label>
						<textarea
							id="ws-<?php echo $row->id; ?>-name"
							name="name"
							maxlength="60"
						><?php echo $row->name; ?></textarea>
					</div>
					
					<div class="control-group control-group-wide">
						<label for="ws-<?php echo $row->id; ?>-description">Description</label>
						<textarea
							id="ws-<?php echo $row->id; ?>-description"
							name="description"
						><?php echo $row->description; ?></textarea>
					</div>
					
					<div class="control-group">
						<label for="ws-<?php echo $row->id; ?>-color">Color</label>
						<div class="workstream-color" style="background-color: #<?php echo $row->color; ?>;"></div>
					</div>
					
					<div class="clear"></div>
					
					<input type="hidden" name="timeline_workstream_id" value="<?php echo $row->id; ?>" />
					
					<div class="form-btns">
						<button type="submit" name="action" value="update">Update</button>
						<button type="submit" name="action" value="delete" onclick="return confirm('Are you sure you want to remove this workstream permanently?')">Delete</button>
						<div class="clear"></div>
					</div>
					
				</div>
				
			<?php echo form_close(); ?>
		</li>
		<?php endforeach; ?>
		
	</ul>
	<?php else: ?>
	<p class="no-data">There are no workstreams set up</p>
	<?php endif; ?>
	
	<?php if (count($workstreams) < $max_workstreams): ?>
	<ul class="data-rows-list">
		<li class="data-row">
			<?php echo form_open(); ?>
			
			<div class="row">
				
				<h3>Add a new workstream</h3>
				
				<div class="control-group control-group-large">
						<label for="ws-new-name">Name</label>
						<textarea
							id="ws-new-name"
							name="name"
							maxlength="60"
						></textarea>
					</div>
					
					<div class="control-group control-group-wide">
						<label for="ws-new-description">Description</label>
						<textarea
							id="ws-new-description"
							name="description"
						></textarea>
					</div>
					
					<div class="control-group">
						<label for="ws-new-color">Color</label>
						<div class="workstream-color" style="background-color: #<?php echo $workstream_colors[count($workstreams)]; ?>;"></div>
						<input type="hidden" name="color" value="<?php echo $workstream_colors[count($workstreams)]; ?>" />
					</div>
					
					<input type="hidden" name="sort_order" value="<?php echo count($workstreams) + 1; ?>" />
					
					<div class="clear"></div>
					
					<div class="form-btns">
						<button type="submit" name="action" value="put">Add</button>
						<div class="clear"></div>
					</div>
				
			</div>
			
			<?php echo form_close(); ?>
		</li>
	</ul>
	<?php endif ?>
	
	<h2><a href="<?php echo site_url('timeline/admin/data-csv'); ?>" style="font-weight: bold;">Click here to download data as a CSV file</a></h2>
	
</div>