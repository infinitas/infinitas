<?php
	echo $this->ModuleLoader->loadDirect('ServerStatus.current', array('current' => $current));
	echo $this->ModuleLoader->loadDirect('ServerStatus.all_time', array('allTime' => $allTime));
	echo $this->ModuleLoader->loadDirect('ServerStatus.last_two_weeks', array('lastTwoWeeks' => $lastTwoWeeks));
	echo $this->ModuleLoader->loadDirect('ServerStatus.last_six_months', array('lastSixMonths' => $lastSixMonths));
	echo $this->ModuleLoader->loadDirect('ServerStatus.by_hour', array('byHour' => $byHour));
	echo $this->ModuleLoader->loadDirect('ServerStatus.by_day', array('byDay' => $byDay));