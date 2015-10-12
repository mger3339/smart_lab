<?php if ($this->user->admin == 1): ?>

<a
	href="<?php echo site_url('trend-compass'); ?>"
	title="Questionnaire"
>Questionnaire</a>

<a
	href="<?php echo site_url('trend-compass/results'); ?>"
	title="Results"
>Results</a>

<?php else: ?>

<a
	class="trend-compass-btn results-btn"
	href="<?php echo site_url('trend-compass/results'); ?>"
	title="Results"
>Results</a>

<?php endif; ?>