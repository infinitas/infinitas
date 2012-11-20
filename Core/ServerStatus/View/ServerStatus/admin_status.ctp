<?php
	echo $this->Html->tag('div', implode('', array(
		$this->ModuleLoader->loadDirect('ServerStatus.current', array('current' => $current)),
		$this->ModuleLoader->loadDirect('ServerStatus.all_time', array('allTime' => $allTime)),
		$this->ModuleLoader->loadDirect('ServerStatus.last_two_weeks', array('lastTwoWeeks' => $lastTwoWeeks)),
		$this->ModuleLoader->loadDirect('ServerStatus.last_six_months', array('lastSixMonths' => $lastSixMonths)),
		$this->ModuleLoader->loadDirect('ServerStatus.by_hour', array('byHour' => $byHour)),
		$this->ModuleLoader->loadDirect('ServerStatus.by_day', array('byDay' => $byDay))
	)), array('class' => 'row-fluid'));