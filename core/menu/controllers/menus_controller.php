<?php
	class MenusController extends MenuAppController{
		var $name = 'Menus';

		function admin_index(){
			$menus = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'type',
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('menus','filterOptions'));
		}
	}