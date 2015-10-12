<div class="admin-content client-applications">
	
	<h2><span><?php echo $client->name; ?></span> applications</h2>
	
	<div class="add-row">
		<button class="add-row-btn" data-url="<?php echo "super-admin/client-applications/{$client->id}/add"; ?>">Add a new application</button>
		<div class="clear"></div>
		<ul class="data-rows-list">
			<li class="data-row"></li>
		</ul>
	</div>
	
	<?php if ($client->applications && ! empty($client->applications)): ?>
	
	<ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("super-admin/client-applications/{$client->id}/sort"); ?>">
		<?php foreach ($client->applications as $row): ?>
		<li class="data-row" data-id="<?php echo $row->id; ?>">
			<?php echo $this->load->view('super_admin/client_applications/partials/client_application_row', array(
				'row'			=> $row,
				'applications'	=> $applications,
			), TRUE); ?>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php else: ?>
	
	<p class="no-data">No applications have yet been assigned for <?php echo $client->name; ?></p>
	
	<?php endif; ?>
	
</div>