<?php
if (empty($userRegistrations)) {
	$userRegistrations = ClassRegistry::init('Users.User')->getRegistrationChartData();
}

$labels = array();
foreach (array_keys($userRegistrations) as $k => $v) {
	if ($k % 2 != 0) {
		$v = '';
	}
	$labels[] = $v;
}
$chart = $this->Charts->draw(
	array(
		'bar' => array(
			'type' => 'vertical_group'
		)
	),
	array(
		'data' => array_values($userRegistrations),
		'axes' => array(
			'x' => $labels,
			'y' => true
		),
		'size' => array('width' => 930, 'height' => 175),
		'extra' => array('html' => array('class' => 'chart')),
		'spacing' => array(
			'padding' => 2,
			'grouping' => 9,
			'width' => 22,
			'type' => 'absolute'
		),
		'legend' => array(
			'position' => 'right',
			'order' => 'default',
			'labels' => array(
				__d('users', 'New Users')
			)
		),
	)
);
echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('h1', __d('users', 'Registrations per month')),
	$chart
)), array('class' => 'dashboard span6'));