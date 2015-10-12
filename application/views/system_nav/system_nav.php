<div id="system-nav">
	<div class="system-nav-items">
		<?php foreach ($system_nav_items as $id => $attr): ?>
			<?php if ( ! empty($attr['view'])): ?>
		<div id="<?php echo $id; ?>" class="system-nav-item">
			<?php echo $attr['view']; ?> 
		</div>
			<?php endif; ?>
		<?php endforeach; ?> 
	</div>
	<div class="system-nav-btns">
		<?php foreach ($system_nav_items as $id => $attr): ?>
		<a id="<?php echo $id; ?>-nav-btn" class="system-nav-btn" title="<?php echo $attr['title']; ?>" data-item-id="#<?php echo $id; ?>"
			<?php echo (is_null($attr['href'])) ? '' : 'href="' . $attr['href'] . '"'; ?>>
		</a>
		<?php endforeach; ?>
		<div class="clear"></div>
	</div>
</div>