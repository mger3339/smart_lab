<div class="admin-content client-application-modules">
	
	<h2><span><?php echo $client->name; ?></span> <span><?php echo $application->name; ?></span> modules</h2>
	
	<ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("super_admin/client_application_modules/sort_client_application_modules/{$client->id}/{$application->id}"); ?>">
		<?php foreach ($modules as $row): ?>
		<li class="data-row" data-id="<?php echo $row->id; ?>">
			<?php echo $this->load->view('super_admin/client_application_modules/partials/client_application_module_row', array(
				'client_id'		=> $client->id,
				'row'			=> $row,
			), TRUE); ?>
		</li>
		<?php endforeach; ?>
	</ul>
	
</div>