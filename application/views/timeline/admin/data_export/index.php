<?php

if (count($workstreams) > 0)
{
	foreach ($workstreams as $row)
	{
		echo '"' . $row->name . ' milestones:"' . "\n";
		
		if (count($row->milestones) > 0)
		{
			echo '"Title",';
			echo '"Description",';
			echo '"Start date",';
			echo '"End date"';
			echo "\n";
			
			foreach ($row->milestones as $milestone)
			{
				echo '"' . $milestone->title . '",';
				echo '"' . $milestone->description . '",';
				echo '"' . strftime("%Y-%m-%d", strtotime($milestone->start_date)) . '",';
				echo '"' . strftime("%Y-%m-%d", strtotime($milestone->end_date)) . '"';
				echo "\n";
			}
		}
		else
		{
			echo '"No data available"';
		}
		
		echo "\n";
		echo '" "';
		echo "\n";
	}
}
else
{
	echo '"No data available"';
}