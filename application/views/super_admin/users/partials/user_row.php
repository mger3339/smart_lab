<?php $row_id = 'user-' . $row->id; ?>

<div id="<?php echo $row_id; ?>" class="row">
	
	<div class="section row-btns">
		<?php if ($this->user->id != $row->id): ?>
		<?php echo form_open("super-admin/users/delete/{$row->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this user?\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this user?\n")); ?>
			<button class="delete-row-btn" type="submit" name="delete" value="TRUE">Delete</button>
		<?php echo form_close(); ?>
		<?php endif; ?>
		<button class="edit-row-btn" data-url="<?php echo "super-admin/users/edit/{$row->id}"; ?>">Edit</button>
		<div class="clear"></div>
	</div>
	
	<div class="section">
		
		<div class="control-group control-group-large control-group-wide">
			<label for="<?php echo $row_id; ?>-name">Full name</label>
			<p><?php echo $row->fullname; ?></p>
		</div>
		
		<div class="control-group">
			<label>Super-admin admin</label>
			<p>Admin: <strong>&nbsp;<?php if ($row->super_admin_admin == 1): ?>Yes<?php else: ?>No<?php endif; ?></strong></p>
		</div>
		
		<div class="control-group">
			<label>Active</label>
			<p>Active: <strong>&nbsp;<?php if ($row->active == 1): ?>Yes<?php else: ?>No<?php endif; ?></strong></p>
		</div>
		
		<div class="clear"></div>
		
		<div class="control-group control-group-wide">
			<label>Email</label>
			<p><a href="mailto:<?php echo $row->email; ?>" title="<?php echo $row->email; ?>"><?php echo $row->email; ?></a></p>
		</div>
		
		<div class="clear"></div>
		
		<div class="control-group">
			<label>Created</label>
			<p><?php echo strftime("%a %e %b %Y, %T", $row->created); ?></p>
		</div>
		
		<?php if ($row->modified > $row->created): ?>
		<div class="control-group">
			<label>Last Modified</label>
			<p><?php echo strftime("%a %e %b %Y, %T", $row->modified); ?></p>
		</div>
		<?php endif; ?>
		
		<?php if ($row->last_login): ?>
		<div class="control-group">
			<label>Last login</label>
			<p><?php echo strftime("%a %e %b %Y, %T", $row->last_login); ?></p>
		</div>
		<?php endif; ?>
		
		<div class="clear"></div>
		
	</div>
	
</div>