<?php
/**
 * PluginsController
 *
 * @package Infinitas.Installer.Controller
 */

/**
 * PluginsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class PluginsController extends InstallerAppController {
/**
 * Plugin dashboard
 *
 * @return void
 */
	public function admin_dashboard() {

	}

/**
 * List plugins
 *
 * @return void
 */
	public function admin_index() {
		$plugins = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;

		$filterOptions['fields'] = array(
			'name',
			'author',
			'version',
			'core' => Configure::read('CORE.core_options'),
			'active' => Configure::read('CORE.active_options')
		);

		$this->set(compact('plugins', 'filterOptions'));
	}

/**
 * Disable adding manually
 *
 * @return void
 */
	public function admin_add() {
		$this->notice(
			__d('insatller', 'Nothing to see, move along'),
			array(
				'level' => 'warning',
				'redirect' => true
			)
		);
	}

/**
 * Edit plugin details
 *
 * @return void
 */
	public function admin_edit() {
		self::admin_add();
	}

/**
 * Plugin install method
 *
 * @return void
 */
	public function admin_install() {
		$this->notice['saved'] = array(
			'message' => 'The selected items were installed successfully',
			'redirect' => ''
		);

		if($this->request->data) {
			try{
				unset($this->request->data['action']);
				$this->Plugin->processInstall($this->request->data);

				$this->notice('saved');
			}
			catch(Exception $e) {
				$this->notice(
					$e->getMessage(),
					array(
						'level' => 'warning'
					)
				);
			}
		}

		$this->set('possibleThemes', ClassRegistry::init('Themes.Theme')->notInstalled());
		$this->saveRedirectMarker();
	}

/**
 * Plugin update method
 */
	public function admin_update_infinitas() {

	}

}