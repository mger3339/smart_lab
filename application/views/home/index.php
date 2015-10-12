<div class="content">
	<h3><?php echo sprintf(lang('home_welcome_message'), $this->user->fullname); ?></h3>
	<?php if ( count($this->client->applications) > 1 ): ?>
	<div class="applications">
		<ul>
			<?php foreach ($this->client->applications as $application): ?>
                <?php if ($application->active == 1 || $this->user->admin == 1): ?>
                    <li>
                        <?php if (( $application->commence && $application->commence > time() && $this->user->admin != 1 ) || ( $application->expire && $application->expire < time() && $this->user->admin != 1 )): ?>
                            <a class="<?php echo $application->application; ?>" title="<?php echo $application->name; ?>">
                        <?php else: ?>
                            <a class="<?php echo $application->application; ?>" href="<?php echo site_url("launch/{$application->id}"); ?>" title="<?php echo $application->name; ?>">
                        <?php endif; ?>
                            <span class="icon" style="color:#<?php echo $application->accent_color; ?>"></span>
                            <span class="title" style="color:#<?php echo $application->accent_color; ?>"><?php echo $application->name; ?></span>
                        </a>
                    </li>
                <?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<div class="clear"></div>
	</div>
	<?php endif; ?>

</div>