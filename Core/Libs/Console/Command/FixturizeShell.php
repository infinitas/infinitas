<?php
	/**
	 * A CakePHP shell to create a fixture using the values of any given database table so one does not have to type it up manually.
	 *
	 * Copyright 2008, Debuggable, Ltd.
	 * Hibiskusweg 26c
	 * 13089 Berlin, Germany
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright Copyright 2008, Debuggable, Ltd.
	 * @version 1.0
	 * @author Felix Geisendörfer <felix@debuggable.com>, Tim Koschützki <tim@debuggable.com>
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 */
	App::import(array('Model', 'AppModel', 'File'));
	class FixturizeShell extends Shell{
		function main() {
			if ($this->args && $this->args[0] == '?') {
				return $this->out('Usage: ./cake fixturize <table> [-force] [-reindex]');
			}
			$options = array(
				'force' => false,
				'reindex' => false,
				'all' => false,
			);
			foreach ($this->params as $key => $val) {
				foreach ($options as $name => $option) {
					if (isset($this->params[$name]) || isset($this->params['-'.$name]) || isset($this->params[$name{0}])) {
						$options[$name] = true;
					}
				}
			}

			if ($options['all']) {
				$db = ConnectionManager::getDataSource('default');
				$this->args = $db->listSources();
			}

			if (empty($this->args)) {
				return $this->err('Usage: ./cake fixturize <table>');
			}

			foreach ($this->args as $table) {
				$name = Inflector::classify($table);
				$Model = new AppModel(array(
					'name' => $name,
					'table' => $table,
				));

				$file = sprintf('%stests/fixtures/%s_fixture.php', APP, Inflector::underscore($name));
				$File = new File($file);
				if ($File->exists() && !$options['force']) {
					$this->err(sprintf('File %s already exists, use --force option.', $file));
					continue;
				}
				$records = $Model->find('all');

				$out = array();
				$out[] = '<?php';
				$out[] = '';
				$out[] = sprintf('class %sFixture extends CakeTestFixture {', $name);
				$out[] = sprintf('	var $name = \'%s\';', $name);
				$out[] = '	var $records = array(';
				foreach ($records as $record) {
					$out[] = '		array(';
					if ($options['reindex']) {
						foreach (array('old_id', 'vendor_id') as $field) {
							if ($Model->hasField($field)) {
								$record[$name][$field] = $record[$name]['id'];
								break;
							}
						}
						$record[$name]['id'] = String::uuid();
					}
					foreach ($record[$name] as $field => $val) {
						$out[] = sprintf('			\'%s\' => \'%s\',', addcslashes($field, "'"), addcslashes($val, "'"));
					}
					$out[] = '		),';
				}
				$out[] = '	);';
				$out[] = '}';
				$out[] = '';
				$out[] = '?>';

				$File->write(join("\n", $out));
				$this->out(sprintf('-> Create %sFixture with %d records (%d bytes) in "%s"', $name, count($records), $File->size(), $file));
			}
		}
	}

?>