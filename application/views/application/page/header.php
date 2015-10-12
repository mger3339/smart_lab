<div id="header">
	
	<div class="content">
		
		<h1><?php echo $this->client->name; ?></h1>
		
		<h2><?php echo $this->application->name; ?></h2>
		
		<?php if ($admin_navigation_view): ?>
		<a
			id="app-navigation-toggle"
			title="Configure"
			<?php if ($admin_area): ?>
			class="admin"
			data-nav-mode="admin"
			<?php else: ?>
			data-nav-mode="main"
			<?php endif; ?>
		></a>
		<?php endif; ?>
		
		<div class="clear"></div>
		
	</div>
	
	<div
		id="main-navigation"
		<?php if ($admin_area): ?>
		style="left: -100%;"
		<?php endif; ?>
	>
		
		<div id="app-main-navigation" class="navigation-content">
			
			<div class="content">
				
				<?php echo $navigation_view; ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>
		
		<?php if ($admin_navigation_view): ?>
		<div id="app-admin-navigation" class="navigation-content">
			
			<div class="content">
				
				<?php echo $admin_navigation_view; ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>
		<?php endif; ?>
		
		<div class="clear"></div>
		
	</div>
	
</div>