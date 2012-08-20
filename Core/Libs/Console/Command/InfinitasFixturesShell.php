<?php
	App::uses('FixtureTask', 'Console/Command/Task');

	class InfinitasFixturesShell extends AppShell {
		public $uses = array();

		public $tasks = array('Fixture', 'Libs.InfinitasFixtureBake');

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
			$this->h1('Infinitas Fixtures');

			$this->out('[G]enerate');
			$this->out('[U]pdate');
			$this->out('[I]mport');
			$this->out('[Q]uit');

			$method = strtoupper($this->in(__('What would you like to do?'), array_keys($this->__options)));

			$method = $this->__options[$method];
			if(!is_callable(array($this, $method))) {
				$this->out(__('You have made an invalid selection. Please choose an option from above.'));
			}
			else{
				$this->{$method}();
			}

			$this->main();
		}

		public function generate() {
			$plugins = $this->_selectPlugins(true, 'all');
			if(!$plugins) {
				return false;
			}

			foreach($plugins as $plugin) {
				$this->__generateFixture($plugin, true);
			}
		}

		public function update() {
			$plugins = $this->_selectPlugins(true, 'all');
			if(!$plugins) {
				return false;
			}

			foreach($plugins as $plugin) {
				$this->__generateFixture($plugin, false);
			}
		}

		public function import() {

		}

		private function __generateFixture($plugin, $new) {
			$models = App::objects('Model', InfinitasPlugin::path($plugin) . 'Model', false);
			$models = array_combine(range(1, count($models)), $models);

			foreach($models as $k => $model) {
				$models[$k] = Inflector::humanize(str_replace('.php', '', $model));
			}
			$models['A'] = 'all';
			$models['B'] = 'back';

			$this->tabbedList($models);

			$option = null;
			while(!$option) {
				$option = strtoupper($this->in(__('Which model would you like a fixture for')));
			}

			if(!isset($models[$option]) || $option == 'B') {
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