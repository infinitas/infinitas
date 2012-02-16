<?php
	/**
	 *
	 *
	 */
	App::uses('File', 'Utility');
	class RobotsController extends WebmasterAppController {
		public $uses = array();

		/**
		 * takes your robots.txt
		 */
		public function admin_edit(){
			Configure::write('Wysiwyg.editor', 'text');
			$File = new File(APP . 'webroot' . DS . 'robots.txt', true);

			if(isset($this->request->data['robots']) && !empty($this->request->data['robots'])){
				$content = $this->request->data['robots'];
				$sitemap = sprintf('Sitemap: %ssitemap.xml', Router::url('/', true));
				if (strpos($content, $sitemap) === false) {
					$content = $sitemap . "\n" . $content;
				}
				if($File->write($content)){
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

			if(!isset($this->request->data['robots'])){
				$this->request->data['robots'] = $File->read();
			}
		}
	}