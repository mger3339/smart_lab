<ul>
	<?php foreach ($super_admin_areas as $area => $properties): ?>
	<li<?php if ($area == $current_area): ?> class="active"<?php endif; ?>>
		<a 
		href="<?php echo site_url('super-admin/' . $area); ?>"
		title="<?php echo $properties['title']; ?>"
		><?php echo $properties['title']; ?></a>
	</li>
	<?php endforeach; ?>
	<li class="logout">
		<a href="<?php echo site_url('logout'); ?>" title="Logout">Logout</a>
	</li>
</ul>