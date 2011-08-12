<?php
	App::import('lib', 'Libs.InfinitasAppShell');

	class InfinitasFixturesShell extends InfinitasAppShell {
		public $uses = array();

		public $tasks = array('Infinitas', 'Fixture', 'InfinitasFixtureBake');

		/**
		 * @brief map options to methods
		 * @var <type>
		 */
		private $__options = array(
			'G' => 'generate',
			'U' => 'update',
			'I' => 'import',
			'Q' => 'quit'
		);

		public function main() {
			Configure::write('debug', 2);
			$this->Infinitas->h1('Infinitas Fixtures');

			$this->Infinitas->out('[G]enerate');
			$this->Infinitas->out('[U]pdate');
			$this->Infinitas->out('[I]mport');
			$this->Infinitas->out('[Q]uit');

			$method = strtoupper($this->in(__('What would you like to do?', true), array_keys($this->__options)));

			$method = $this->__options[$method];
			if(!is_callable(array($this, $method))){
				$this->out(__('You have made an invalid selection. Please choose an option from above.', true));
			}
			else{
				$this->{$method}();
			}

			$this->main();
		}

		public function generate(){
			$plugins = $this->_selectPlugins(true, 'all');
			if(!$plugins){
				return false;
			}
			
			foreach($plugins as $plugin){
				$this->__generateFixture($plugin, true);
			}
		}

		public function update(){
			$plugins = $this->_selectPlugins(true, 'all');
			if(!$plugins){
				return false;
			}

			foreach($plugins as $plugin){
				$this->__generateFixture($plugin, false);
			}
		}

		public function import(){

		}

		private function __generateFixture($plugin, $new){
			$models = App::objects('model', App::pluginPath($plugin) . 'models', false);
			$models = array_combine(range(1, count($models)), $models);
			
			foreach($models as $k => $model) {
				$models[$k] = Inflector::humanize(str_replace('.php', '', $model));
			}
			$models['A'] = 'all';
			$models['B'] = 'back';
			
			$this->tabbedList($models);

			$option = null;
			while(!$option){
				$option = strtoupper($this->in(__('Which model would you like a fixture for', true)));
			}

			if(!isset($models[$option]) || $option == 'B'){
				return false;
			}

			if($option != 'A') {
				$models = array($models[$option]);
			}

			foreach($models as $model) {
				$this->InfinitasFixtureBake->setup($plugin . '.' . $model, $new);
			}
		}
	}