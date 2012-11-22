<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */

	class R4f6e8208f98046bbac83240a6318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Themes version 0.9.1';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Themes';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'core_themes' => array(
					'default_layout' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'licence'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'core_themes' => array('default_layout',),
			),
		),
	);

	public $fixtures = array(
		'core' => array(
			'Theme' => array(
				array(
					'id' => '2f5a3a81-f1cc-44b4-0000-199b6318cd63',
					'name' => 'infinitas',
					'description' => 'The default theme for Infinitas CMS',
					'author' => 'Infinitas CMS',
					'url' => 'http://infinitas-cms.org',
					'update_url' => 'http://infinitas-cms.org',
					'licence' => 'MIT',
					'default_layout' => null,
					'active' => 1,
					'core' => 0,
					'created' => '2012-11-22 01:39:54',
					'modified' => '2012-11-22 01:39:54'
				)
			)
		)
	);


	/**
	* Before migration callback
	*
	* @param string $direction, up or down direction of migration process
	* @return boolean Should process continue
	* @access public
	*/
		public function before($direction) {
			return true;
		}

	/**
	* After migration callback
	*
	* @param string $direction, up or down direction of migration process
	* @return boolean Should process continue
	* @access public
	*/
		public function after($direction) {
			return true;
		}
	}