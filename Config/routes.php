<?php
	require CAKE . 'Config' . DS . 'routes.php';
	
	App::uses('InfinitasRouting', 'Routes.Lib');

	InfinitasRouting::setup();