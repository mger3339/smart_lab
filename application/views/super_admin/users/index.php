<div class="admin-content users">
	
	<h2>Super admin users</h2>
	
	<div class="add-row">
		<button class="add-row-btn" data-url="<?php echo "super-admin/users/add"; ?>">Add a new super admin user</button>
		<div class="clear"></div>
		<ul class="data-rows-list">
			<li class="data-row"></li>
		</ul>
	</div>
	
	<ul class="data-rows-list">
		<?php foreach ($super_admin_users as $row): ?>
		<li class="data-row">
			<?php echo $this->load->view('super_admin/users/partials/user_row', array(
				'row'					=> $row,
			), TRUE); ?>
		</li>
		<?php endforeach; ?>
	</ul>
	
</div>