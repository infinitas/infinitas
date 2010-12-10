<?php
	echo $this->element('modules/admin/current', array('current' => $current, 'plugin' => 'server_status'));
	echo $this->element('modules/admin/all_time', array('allTime' => $allTime, 'plugin' => 'server_status'));
	echo $this->element('modules/admin/last_two_weeks', array('lastTwoWeeks' => $lastTwoWeeks, 'plugin' => 'server_status'));
	echo $this->element('modules/admin/last_six_months', array('lastSixMonths' => $lastSixMonths, 'plugin' => 'server_status'));
	echo $this->element('modules/admin/by_hour', array('byHour' => $byHour, 'plugin' => 'server_status'));
	echo $this->element('modules/admin/by_day', array('byDay' => $byDay, 'plugin' => 'server_status'));