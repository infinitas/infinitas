<?php
	/**
	 *
	 *
	 */
	class RobotsController extends WebmasterAppController {
		public $uses = array();

		/**
		 * takes your robots.txt
		 */
		public function admin_edit(){
			Configure::write('Wysiwyg.editor', 'text');
			$File = new File(APP . 'webroot' . DS . 'robots.txt');

			if(isset($this->request->data['Robot']['robots']) && !empty($this->request->data['Robot']['robots'])){
				if($File->write(sprintf('Sitemap: %ssitemap.xml%s%s', Router::url('/', true), "\n", $this->request->data['Robot']['robots']))){
					$this->notice(
						__('Robots file updated'),
						array(
							'redirect' => array('controller' => 'webmaster', 'action' => 'dashboard')
						)
					);
				}

				$this->notice(
					__('There was a problem updating the robots file'),
					array(
						'level' => 'error'
					)
				);
			}

			if(!isset($this->request->data['Robot']['robots'])){
				$this->request->data['Robot']['robots'] = $File->read();
			}
		}
	}