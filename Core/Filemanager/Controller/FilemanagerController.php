<?php
	/**
	*/
	class FilemanagerController extends AppController {
		public $uses = array('Filemanager.Files', 'Filemanager.Folders');

		public function admin_index() {
			$path = '/';
			if(!empty($this->request->params['pass'])) {
				$path = implode('/', $this->request->params['pass']);
			}

			$this->Folders->recursive = 2;
			$folders = $this->Folders->find(
				'all',
				array(
					'fields' => array(
					),
					'conditions' => array(
						'path' => $path
					),
					'order' => array(
						'name' => 'ASC'
					)
				)
			);

			$this->Files->recursive = 2;
			$files = $this->Files->find(
				'all',
				array(
					'fields' => array(
						),
					'conditions' => array(
						'path' => $path
						),
					'order' => array(
						'name' => 'ASC'
						)
					)
				);

			$this->set(compact('files', 'folders'));
		}

		public function admin_view() {
			if(!empty($this->request->params['pass'])) {
				$path = implode('/', $this->request->params['pass']);
			}

			if (!$path || !is_file(APP.$path)) {
				$this->notice(
					__('Please select a file first'),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}

			$this->set('path', APP.$path);
		}

		public function admin_download($file = null) {
			if (!$file) {
				$this->notice(
					__('Please select a file first'),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}
			//  @todo mediaViews
		}

		public function admin_delete($file = null) {
			if (!$file) {
				$this->notice('invalid');
			}

			if ($this->FileManager->delete($file)) {
				$this->notice('deleted');
			}

			$this->notice('not_deleted');
		}
		
		public function admin_ajax_tree() {
			if(empty($this->request->data['dir']) || !is_dir(APP . $this->request->data['dir'])) {
				//throw new Exception('Invalid dir');
			}
			
			Configure::write('Themes.default_layout_admin', 'ajax');
			$dir = $this->request->data['dir'];
			if($dir == '/') {
				$dir = APP;
			}
			
			$this->set('files', $this->__getFiles($dir));
			$this->set('dir', $dir);
		}
		
		public function admin_ajax_folder() {
			$this->admin_ajax_tree();
		}
		
		private function __getFiles(&$dir) {
			if($dir == APP) {
				return $this->__getPlugins();
			}
			
			if($dir == APP . 'webroot' . DS . 'img') {
				$files = scandir($dir);
				natcasesort($files);
				
				foreach($files as &$file) {
					$file = APP . 'webroot' . DS . 'img' . $file;
				}
				
				return $files;
			}
			
			if(is_dir($dir)) {
				$files = scandir($dir);
				natcasesort($files);
				
				foreach($files as $k => &$file) {
					if(substr($file, 0, 1) == '.') {
						unset($files[$k]);
						continue;
					}
					
					$file = $dir . $file;
				}
				
				return $files;
			}
		}
		
		private function __getPlugins() {
			$plugins = InfinitasPlugin::listPlugins('loaded');
			natcasesort($plugins);
			
			foreach($plugins as &$plugin) {
				$plugin = InfinitasPlugin::path($plugin) . 'webroot' . DS . 'img' . DS;
			}
			
			$plugins[] = APP . DS . 'webroot' . DS . 'img' . DS;
			
			return $plugins;
		}
		
		private function __getPluginFiles($dir) {
			if(!is_dir($dir)) {
				return array();
			}
			
			$files = scandir($dir);
			natcasesort($files);
			
			foreach($files as $k => &$file) {
				if(substr($file, 0, 1) == '.') {
					unset($files[$k]);
					continue;
				}
				
				$file = $dir . $file . DS;
			}
			
			return $files;
		}
	}