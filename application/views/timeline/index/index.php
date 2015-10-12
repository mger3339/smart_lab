<?php
	$start_date_time = new DateTime($timeline_settings->calendar_start);
	$end_date_time = new DateTime($timeline_settings->calendar_end);
	$total_days = intval($end_date_time->diff($start_date_time)->format("%a")) + 1;
	
	$date = $timeline_settings->calendar_start;
	
	$year = 0;
	$quarter = 0;
	$month = 0;
	$week = 0;
	
	$year_days = 0;
	$quarter_days = 0;
	$month_days = 0;
	$week_days = 0;
	
	$calendar_years = array();
	$calendar_quarters = array();
	$calendar_months = array();
	$calendar_weeks = array();
	
	$week_count = 0;
	
	while (strtotime($date) <= strtotime($timeline_settings->calendar_end))
	{
		$date_year = intval(date("Y", strtotime($date)));
		
		$date_quarter = ceil((intval(date("n", strtotime($date))) * 4) / 12);
		
		$date_month = intval(date("n", strtotime($date)));
		$date_month_name = date("M", strtotime($date));
		
		$date_week = intval(date("W", strtotime($date)));
		
		if ($year != $date_year)
		{
			$year = $date_year;
			$year_days = 0;
			$calendar_years[$year] = array();
		}
		
		$year_days++;
		$calendar_years[$year]['days'] = $year_days;
		
		if ($quarter != $date_quarter)
		{
			$quarter = $date_quarter;
			$quarter_days = 0;
			$calendar_quarters[$year . '-' . $quarter] = array();
			$calendar_quarters[$year . '-' . $quarter]['quarter'] = $quarter;
		}
		
		$quarter_days++;
		$calendar_quarters[$year . '-' . $quarter]['days'] = $quarter_days;
		
		if ($month != $date_month)
		{
			$month = $date_month;
			$month_days = 0;
			$calendar_months[$year . '-' . $month] = array();
			$calendar_months[$year . '-' . $month]['name'] = $date_month_name;
		}
		
		$month_days++;
		$calendar_months[$year . '-' . $month]['days'] = $month_days;
		
		if ($week != $date_week)
		{
			$week_count++;
			$week = $date_week;
			$week_days = 0;
			$calendar_weeks[$week_count] = array();
			$calendar_weeks[$week_count]['week'] = $week;
		}
		
		$week_days++;
		$calendar_weeks[$week_count]['days'] = $week_days;
		
		$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
?>

<div id="index">
	
	<div id="list-column">
		
		<div id="list-column-head">
			<?php echo $workstreams_list_head_view; ?>
		</div>
		
		<div id="list-column-body-wrap">
			<div id="list-column-workstreams" class="list-column-body">
				<?php echo $workstreams_list_column_view; ?>
			</div>
			<div id="list-column-milestones" class="list-column-body">
				<?php echo $milestones_list_column_view; ?>
			</div>
		</div>
		
	</div>
	
	<div id="calendar">
		
		<div id="calendar-back-wrap">
			
			<div id="calendar-back" class="<?php echo $timeline_settings->calendar_division; ?>" style="width: <?php echo $total_days * $calendar_scale; ?>px;">
				
				<?php foreach ($calendar_years as $year => $year_attr): ?>
				<div class="year" style="width: <?php echo ($year_attr['days'] * $calendar_scale) - 2; ?>px;">
					<span><?php echo $year; ?></span>
				</div>
				<?php endforeach; ?>
				
				<div class="clear"></div>
				
				<div class="calendar-units">
					
					<?php switch ($timeline_settings->calendar_division): case 'weeks': ?>
					
					<?php foreach ($calendar_months as $year_month => $month_attr): ?>
					<div class="week-month" style="width: <?php echo ($month_attr['days'] * $calendar_scale) - 1; ?>px;">
						<span><?php echo ucfirst($month_attr['name']); ?></span>
					</div>
					<?php endforeach; ?>
					<div class="clear"></div>
					<?php foreach ($calendar_weeks as $week => $week_attr): ?>
					<div class="cal-unit week" style="width: <?php echo ($week_attr['days'] * $calendar_scale) - 1; ?>px;">
						<span><?php echo $week_attr['week']; ?></span>
					</div>
					<?php endforeach; ?>
					
					<?php break; case 'months': ?>
					
					<?php foreach ($calendar_months as $year_month => $month_attr): ?>
					<div class="cal-unit month" style="width: <?php echo ($month_attr['days'] * $calendar_scale) - 1; ?>px;">
						<span><?php echo ucfirst($month_attr['name']); ?></span>
					</div>
					<?php endforeach; ?>
					
					<?php break; case 'quarters': ?>
					
					<?php foreach ($calendar_quarters as $year_quarter => $quarter_attr): ?>
					<div class="cal-unit quarter" style="width: <?php echo ($quarter_attr['days'] * $calendar_scale) - 1; ?>px;">
						<span>Q<?php echo $quarter_attr['quarter']; ?></span>
					</div>
					<?php endforeach; ?>
					
					<?php break; default: // years ?>
					
					<?php foreach ($calendar_years as $year => $year_attr): ?>
					<div class="year-unit" style="width: <?php echo ($year_attr['days'] * $calendar_scale); ?>px;">
						<span><?php echo $year; ?></span>
					</div>
					<?php endforeach; ?>
					
					<?php break; endswitch; ?>
					
					<div class="clear"></div>
					
				</div>
				
			</div>
		</div>
		
		<div id="calendar-body-wrap">
			<div id="calendar-workstreams" class="calendar-body" style="width: <?php echo $total_days * $calendar_scale; ?>px;">
				<?php echo $workstreams_calendar_view; ?>
			</div>
			<div id="calendar-milestones" class="calendar-body" style="width: <?php echo $total_days * $calendar_scale; ?>px;">
				<?php echo $milestones_calendar_view; ?>
			</div>
		</div>
		
	</div>
	
	<div class="clear"></div>
	
</div>

<div id="workstream-wrap"></div>

<div id="milestone-wrap" class="bg-workstream-color" style="background-color: #<?php echo $workstreams[0]->color; ?>;"></div>