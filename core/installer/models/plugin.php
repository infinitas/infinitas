<?php
class Plugin extends InstallerAppModel {
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'message' => __d('installer', 'All plugins are required to have a valid unique identifier based on the RFC4122 definition.', true)
				),
			),
			'internal_name' => array(
				'unique' => array(
					'rule' => 'isUnique',
					'message' => __d('installer', 'You already have a plugin with this name installed', true)
				)
			)
		);
	}

	public function installPlugin($pluginName, $options = array()) {
		$options = array_merge(
			array(
				'sampleData' => false,
				'installRelease' => true
			),
			$options
		);

		extract($options);

		$pluginDetails = $this->__loadPluginDetails($pluginName);

		if($pluginDetails !== false && isset($pluginDetails['id'])) {
			$pluginDetails['dependancies'] = json_encode($pluginDetails['dependancies']);
			$pluginDetails['internal_name'] = $pluginName;
			$pluginDetails['enabled'] = true;
			$pluginDetails['core'] = strpos(App::pluginPath($pluginName), APP . 'core' . DS) !== false;

			if($this->save($pluginDetails)) {
				if($installRelease === true) {
					$installed = $this->__processRelease($pluginName, $sampleData);
				}
				else {
					$installed = true;
				}

				return $installed;
			}
			else {
				return false;
			}
		}
	}

	private function __loadPluginDetails($pluginName) {
		$configFile = App::pluginPath($pluginName) . 'config' . DS . 'config.json';
		
		return file_exists($configFile) ? Set::reverse(json_decode(file_get_contents($configFile))) : false;
	}
	
	private function __processRelease($pluginName, $sampleData = false) {
		App::import('Lib', 'Installer.ReleaseVersion');

		try {
			$Version = new ReleaseVersion();

			$mapping = $Version->getMapping($pluginName);

			$latest = array_pop($mapping);

			return $Version->run(array(
				'type' => $plugin,
				'version' => $latest['version'],
				'sample' => $sampleData
			));
		}
		catch(Exception $e) {
			return false;
		}

		return true;
	}

}