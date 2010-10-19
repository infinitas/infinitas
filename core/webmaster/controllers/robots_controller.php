<?php
	/**
	 *
	 *
	 */
	class RobotsController extends WebmasterAppController{
		public $name = 'Robots';

		public $uses = array();

		/**
		 * takes your robots.txt
		 */
		public function admin_edit(){
			Configure::write('Wysiwyg.editor', 'text');
			$File = new File(APP . 'webroot' . DS . 'robots.txt');

			if(isset($this->data['Robot']['robots']) && !empty($this->data['Robot']['robots'])){
				if($File->write(sprintf('Sitemap: %ssitemap.xml%s%s', Router::url('/', true), "\n", $this->data['Robot']['robots']))){
					$this->notice(
						__('Robots file updated', true),
						array(
							'redirect' => array('controller' => 'webmaster', 'action' => 'dashboard')
						)
					);
				}

				$this->notice(
					__('There was a problem updating the robots file', true),
					array(
						'level' => 'error'
					)
				);
			}
			
			if(!isset($this->data['Robot']['robots'])){
				$this->data['Robot']['robots'] = $File->read();
			}
		}
	}