<?php
	/**
	 *
	 *
	 */
	class Module extends ManagementAppModel{
		var $name = 'Module';

		var $virtualFields = array(
			'list_name' => "IF(Module.admin = 1, CONCAT('Admin :: ', Module.name), Module.name)",
			'save_name' => "IF(Module.admin = 1, CONCAT('admin/', Module.module), Module.module)"
		);

		var $actsAs = array(
			'Libs.Sequence' => array(
				'group_fields' => array(
					'position_id'
				)
			)
		);

		var $order = array(
			'Module.position_id' => 'ASC',
			'Module.ordering' => 'ASC'
		);

		var $belongsTo = array(
			'Position' => array(
				'className' => 'Management.ModulePosition',
				'foreignKey' => 'position_id'
			),
			'Management.Group',
			'Theme' => array(
				'className' => 'Management.Theme',
				'foreignKey' => 'theme_id'
			),
		);

		var $hasAndBelongsToMany = array(
			'Route' => array(
				'className' => 'Management.Route',
				'joinTable' => 'core_modules_routes',
				'foreignKey' => 'module_id',
				'associationForeignKey' => 'route_id',
				'unique' => true
			)
		);

		function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->subPath = 'views'.DS.'elements'.DS.'modules'.DS;
		}

		function getModules($position = null, $admin){
			if (!$position) {
				return array();
			}

			$modules = Cache::read('modules.' . $position . '.' . ($admin ? 'admin' : 'user'), 'core');
			if(!empty($modules)){
				return $modules;
			}

			$modules = $this->find(
				'all',
				array(
					'fields' => array(
						'Module.id',
						'Module.name',
						'Module.plugin',
						'Module.content',
						'Module.module',
						'Module.config',
						'Module.show_heading'
					),
					'conditions' => array(
						'Position.name' => $position,
						'Module.admin' => $admin,
						'Module.active' => 1
					),
					'contain' => array(
						'Position' => array(
							'fields' => array(
								'Position.id',
								'Position.name'
							)
						),
						'Group' => array(
							'fields' => array(
								'Group.id',
								'Group.name'
							)
						),
						'Route' => array(
							'fields' => array(
								'Route.id',
								'Route.name',
								'Route.url'
							)
						),
						'Theme' => array(
							'fields' => array(
								'Theme.id',
								'Theme.name'
							)
						)
					)
				)
			);
			Cache::write('modules.' . $position . '.' . ($admin ? 'admin' : 'user'), $modules, 'core');

			return $modules;
		}

		function getModuleList($plugin = null){
			$admin = $non_admin = array();

			$conditions = array();
			$path = APP;
			if ($plugin) {
				$plugin = strtolower($plugin);
				$path   = App::pluginPath($plugin);
				$conditions = array('Module.plugin' => $plugin);
			}

			App::import('File');
			$this->Folder = new Folder($path.$this->subPath);

			$files = $this->Folder->read();

			foreach($files[1] as $file){
				$file = str_replace('.ctp', '', $file);
				$non_admin[$file] = Inflector::humanize($file);
			}

			if(!empty($files[0]) && is_dir($path.$this->subPath.'admin')){
				$this->Folder->cd($path.$this->subPath.'admin');
				$files = $this->Folder->read();

				foreach($files[1] as &$file){
					$file = str_replace('.ctp', '', $file);
					$admin['admin/'.$file] = Inflector::humanize($file);
				}
			}

			return array(
				'admin' => $admin,
				'user' => $non_admin
			);
		}

		function afterSave($created){
			return $this->dataChanged('afterSave');
		}

		function afterDelete(){
			return $this->dataChanged('afterDelete');
		}

		function dataChanged($from){
			App::import('Folder');
			$Folder = new Folder(CACHE . 'core' . DS . 'modules');
			$Folder->delete();

			unset($Folder);

			return true;
		}
	}
