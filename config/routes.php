<?php
	if(App::import('Libs', 'Routes.routing')){
		InfinitasRouting::setup();
	}
	else{
		// some error
	}