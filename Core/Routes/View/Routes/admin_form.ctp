<?php
	/**
	 * add / edit routes for the site
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   Infinitas.routes
	 * @subpackage	Infinitas.routes.views.admin_form
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	echo $this->Form->create();
		echo $this->Infinitas->adminEditHead();
		echo $this->Form->input('id');

		$tabs = array(
			__d('routes', 'Route'),
			__d('routes', 'Configuration')
		);

		$options = Configure::read('Routing.prefixes');
		$options = array_combine($options, $options);

		$contents = array(
			implode('', array(
				$this->Form->input('name'),
				$this->Form->input('url'),
				$this->Html->tag('div', implode('', array(
					$this->Form->input('prefix', array('options' => $options, 'type' => 'select', 'empty' => __d('routes', 'None'))),
					$this->element('Routes.route_select')
				)), array('class' => 'dynamic')),
				$this->Form->input('active'),
				$this->element('Themes.theme_select')
			)),
			implode('', array(
				$this->Html->tag('div', implode('', array(
					$this->Form->input('pass'),
					$this->Form->input('force_backend'),
					$this->Form->input('force_frontend')
				)), array('class' => 'dynamic')),
				$this->Form->input('values'),
				$this->Form->input('rules'),
			))
		);
		echo $this->Design->tabs($tabs, $contents);
	echo $this->Form->end();