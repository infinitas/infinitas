<?php
	App::uses('InstallerLib', 'Installer.Lib');
	
	class InfinitasTheme {
		public static function install() {
			
		}
		
		public static function uninstall() {
			
		}
		
		public static function current() {
			
		}
		
		public static function themes($type = 'installed') {
			switch($type) {
				case 'installed':
					return ClassRegistry::init('Themes.Theme')->installed();
					break;
				
				case 'notInstalled':
					return array_intersect(self::themes('all'), self::themes('installed'));
					break;
				
				default:
				case 'all':
					$return = array();
					foreach(InfinitasPlugin::listPlugins('loaded') as $plugin) {
						foreach(InstallerLib::findThemes($plugin) as $theme) {
							$return[$plugin . '.' . $theme] = Inflector::humanize(Inflector::underscore($plugin . Inflector::camelize($theme)));
						}
					}

					foreach(InstallerLib::findThemes() as $theme) {
						$return[$theme] = Inflector::humanize(Inflector::underscore($theme));
					}
					return $return;
					break;
			}
		}
		
		public static function layouts($theme = null) {
			if($theme) {
				return self::__layoutsFor($theme);
			}
			
			$return = array();
			foreach(self::themes() as $theme) {
				$return[$theme] = self::__layoutsFor($theme);
			}
			
			return $return;
		}
		
		private static function __layoutsFor($theme) {
			$temp = ClassRegistry::init('Themes.Theme')->find(
				'first',
				array(
					'fields' => array(
						'Theme.name'
					),
					'conditions' => array(
						'Theme.id' => $theme
					)
				)
			);
			if(!empty($temp['Theme']['name'])) {
				$theme = $temp['Theme']['name'];
			}
			
			list($plugin, $theme) = pluginSplit($theme);
			
			if(!$plugin) {
				$plugin = self::findPlugin($theme);
			}
			
			$Folder = new Folder(self::themePath($plugin, $theme) . DS . 'Layouts');
			$files = $Folder->read();
			
			$return = array();
			foreach($files[1] as &$file) {
				$file = pathinfo($file);
				$return[$file['filename']] = Inflector::humanize($file['filename']);
			}
			
			return $return;
		}
		
		public static function findPlugin($theme) {
			foreach(self::themes('all') as $pluginTheme => $niceName) {
				list($p, $t) = pluginSplit($pluginTheme);
				if($t == $theme) {
					return $p;
				}
			}
			
			throw new Exception('Could not find selected theme');
		}
		
		/**
		 * @brief generate the path to a plugins theme dir
		 * 
		 * If the specific theme is available it will return the path to the 
		 * theme, if not it will return the path to where the themes for that plugin 
		 * are kept
		 * 
		 * If no plugin is null, it is assumed that the path for app themes are required
		 * 
		 * @access public
		 * 
		 * @param string $plugin the name of the plugin
		 * @param string $theme the name of the theme
		 * 
		 * @return string the path that is requested 
		 */
		public static function themePath($plugin = null, $theme = null) {
			if(!$plugin) {
				if(!$theme) {
					return APP . 'View' . DS . 'Themed';
				}
				
				return APP . 'View' . DS . 'Themed' . DS . $theme;
			}
			
			if(!$theme) {
				return InfinitasPlugin::path($plugin) . 'View' . DS . 'Themed';
			}
			
			return InfinitasPlugin::path($plugin) . 'View' . DS . 'Themed' . DS . $theme;
		}
	}