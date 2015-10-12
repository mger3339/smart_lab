<div class="admin-content clients">

	<h2>Clients<?php if (count($clients) > 1): ?> (<?php echo count($clients); ?>)<?php endif; ?></h2>

	<div class="add-row">
		<button class="add-row-btn" data-url="<?php echo "super-admin/clients/add"; ?>">Add a new client</button>
		<div class="clear"></div>
		<ul class="data-rows-list">
			<li class="data-row"></li>
		</ul>
	</div>

	<?php if ($clients): ?>

	<ul class="data-rows-list">
		<?php foreach ($clients as $row): ?>
		<li class="data-row">
			<?php echo $this->load->view('super_admin/clients/partials/client_row', array(
				'row'					=> $row,
				'country_options'		=> $country_options,
				'time_zone_options'		=> $time_zone_options,
				'currency_options'		=> $currency_options,
			), TRUE); ?>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php else: ?>

	<p class="no-data">There are no clients set up</p>

	<?php endif; ?>

</div>