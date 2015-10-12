<ul>
	<?php foreach ($admin_areas as $area => $properties): ?>
	<li class="<?php echo $area; ?><?php if ($area == $current_area): ?> active<?php endif; ?>">
		<a 
		href="<?php echo site_url('admin/' . $area); ?>"
		title="<?php echo $properties['title']; ?>"
		><?php echo $properties['title']; ?></a>
	</li>
	<?php endforeach; ?>
</ul>