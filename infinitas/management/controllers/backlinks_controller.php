<?php
	/**
	 *
	 *
	 */
	class BacklinksController extends ManagementAppController{
		var $name = 'Backlinks';

		function admin_index(){
			$this->Backlink->find('all');
			$this->set(compact('data'));
		}
	}
?>