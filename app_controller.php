<?php
/**
* Comment Template.
*
* @todo Implement .this needs to be sorted out.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://infinitas-cms.org
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class AppController extends Controller {

	var $view = 'Theme';

	var $helpers = array(
		'Html', 'Form', 'Javascript', 'Session', 'Time',
		'Libs.Infinitas', 'Libs.TagCloud'
	);

	var $components = array(
		'Libs.Infinitas',
		// cake components
		'Session','RequestHandler', 'Auth', 'Acl', 'Security',
		// core components
		'DebugKit.Toolbar', // 'Libs.Cron',
		// components
		'Filter.Filter' => array(
			'actions' => array('admin_index')
		),
		'Libs.Voucher',
		'Libs.MassAction',
		'Events.Event'
	);

	/**
	* actions where viewable will work.
	*/
	var $viewableActions = array(
		'view'
	);


	function beforeFilter() {
		parent::beforeFilter();

		$infinitasJsData['base']	   = (isset($this->base) ? $this->base : '');
		$infinitasJsData['here']	   = (isset($this->here) ? $this->here : '');
		$infinitasJsData['plugin']     = (isset($this->plugin) ? $this->plugin : '');
		$infinitasJsData['name']	   = (isset($this->name) ? $this->name : '');
		$infinitasJsData['action']     = (isset($this->action) ? $this->action : '');
		$params                        = (isset($this->params) ? $this->params : '');
		unset($params['_Token']);
		$infinitasJsData['params']     = $params;
		$infinitasJsData['passedArgs'] = (isset($this->passedArgs) ? $this->passedArgs : '');
		$infinitasJsData['data']	   = (isset($this->data) ? $this->data : '');
		$infinitasJsData['model']	   = $this->modelClass;

		$infinitasJsData['config']     = Configure::getInstance();

		$this->set(compact('infinitasJsData'));

		$this->Security->validatePost = false;

		$this->__setupAuth();

		if (isset($this->data['PaginationOptions']['pagination_limit'])) {
			$this->Infinitas->changePaginationLimit( $this->data['PaginationOptions'], $this->params );
		}

		if (isset($this->params['named']['limit'])) {
			$this->params['named']['limit'] = $this->Infinitas->paginationHardLimit($this->params['named']['limit']);
		}

		if(isset($this->params['form']['action']) && $this->params['form']['action'] == 'cancel'){
			if(isset($this->{$this->modelClass}) && $this->{$this->modelClass}->hasField('locked') && isset($this->data[$this->modelClass]['id'])){
				$this->{$this->modelClass}->unlock($this->data[$this->modelClass]['id']);
			}
			$this->redirect(array('action' => 'index'));
		}

		if (Configure::read('Website.force_www')) {
			$this->Infinitas->forceWwwUrl();
		}

		if (sizeof($this->uses) && (isset($this->{$this->modelClass}->Behaviors) && $this->{$this->modelClass}->Behaviors->attached('Logable'))) {
			$this->{$this->modelClass}->setUserData($this->Session->read('Auth'));
		}

		if(isset($this->RequestHandler)){
			switch(true){
				case $this->RequestHandler->prefers('json'):
					$this->view = 'Libs.Json';
					break;

				case $this->RequestHandler->prefers('rss'):
					;
					break;

				case $this->RequestHandler->prefers('vcf'):
					;
					break;

			} // switch
			// Configure::write('debug', 0);
			// $this->theme = null;
		}

		$this->set('commentModel', 'Comment');

		// @todo remove from acts as and atach when something is viewed.
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin' && !in_array($this->params['action'], $this->viewableActions)) {
			if (isset($this->{$this->modelClass}->Behaviors)) {
				$this->{$this->modelClass}->Behaviors->detach('Viewable');
			}
		}

		//$this->Event->trigger('testEvent');
	}

	function beforeRender(){
		parent::beforeRender();
	}

	function __setupAuth(){
		$this->Auth->allow('*');
		//$this->Auth->allowedActions = array('display', 'login', 'logout');

		if (!isset($this->params['prefix']) || $this->params['prefix'] != 'admin') {
			$this->Auth->allow('*');
		}
		$this->Auth->actionPath   = 'controllers/';
		$this->Auth->authorize    = 'actions';
		$this->Auth->loginAction  = array('plugin' => 'management', 'controller' => 'users', 'action' => 'login');

		$this->Auth->autoRedirect = false;
		$this->Auth->loginRedirect = '/';

		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
			$this->Auth->loginRedirect = '/admin';
		}

		$this->Auth->logoutRedirect = '/';

		$this->Security->blackHoleCallback = 'blackHole';
	}

	function blackHole(&$controller, $error){
		pr('you been blackHoled');
		pr($error);
		exit;
	}



	/**
	* Common methods for the app
	*/
	function comment($id = null) {
		if (!empty($this->data['Comment'])) {
			$message = 'Your comment has been saved and will be available after admin moderation.';
			if (Configure::read('Comments.auto_moderate') === true) {
				$this->data['Comment']['active'] = 1;
				$message = 'Your comment has been saved and is active.';
			}

			if ($this->Post->createComment($id, $this->data)) {
				$this->Session->setFlash(__($message, true));
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('Your comment was not saved. Please check for errors and try again', true));
			}
		}
	}

	function rate($id = null) {
		if (!empty($this->data['Rating'])) {
			if (Configure::read('Rating.require_auth') === true) {
				$this->data['Rating']['user_id'] = $this->Session->read('Auth.User.id');
				if (!$this->data['Rating']['user_id']) {
					$this->Session->setFlash(__('You need to be logged in to rate this item',true));
					$this->redirect('/login');
				}
			}

			$this->data['Rating']['ip'] = $this->RequestHandler->getClientIP();

			if ($this->{$this->modelClass}->rateRecord($this->data)) {
				$this->Session->setFlash(__('Your rating was saved.', true));
			} else {
				$this->Session->setFlash(__('There was a problem submitting your vote', true));
			}
			$this->redirect($this->referer());
		}
	}

	function __getClassName() {
		if (isset($this->params['plugin'])) {
			return Inflector::classify($this->params['plugin']) . '.' . Inflector::classify($this->name);
		} else {
			return Inflector::classify($this->name);
		}
	}

	/**
	* reorder records.
	*
	* uses named paramiters can use the following:
	* - position: sets the position for the record for sequenced models
	*  - possition needs the new possition of the record
	*
	* - direction: for MPTT and only needs the record id.
	*
	* @param int $id the id of the record to move.
	* @return does a redirect to the referer.
	*/
	function admin_reorder($id = null) {
		$model = $this->modelNames[0];

		if (!$id) {
			$this->Session->setFlash('That ' . $model . ' could not be found', true);
			$this->redirect($this->referer());
		}

		$this->$model->id = $id;

		if (!isset($this->params['named']['possition']) && isset($this->$model->actsAs['Libs.Sequence'])) {
			$this->Session->setFlash(__('A problem occured moving the ordered record.', true));
			$this->redirect($this->referer());
		}

		if (!isset($this->params['named']['possition']) && isset($this->$model->actsAs['Tree'])) {
			$this->Session->setFlash(__('A problem occured moving the MPTT record.', true));
			$this->redirect($this->referer());
		}

		if (isset($this->params['named']['possition'])) {
			$this->_orderedMove();
		}

		if (isset($this->params['named']['direction'])) {
			$this->_treeMove();
		}

		$this->redirect($this->referer());
	}

	/**
	* Moving MPTT records
	*
	* This is used for moving mptt records and is called by admin_reorder.
	*/
	function _treeMove(){
		$model = $this->modelNames[0];
		$check = $this->{$model}->find(
			'first',
			array(
				'fields' => array($model.'.id'),
				'conditions' => array($model.'.id' => $this->$model->id),
				'recursive' => -1
			)
		);

		if (empty($check)) {
			$this->Session->setFlash(__('Nothing found to move', true));
			return false;
		}

		switch($this->params['named']['direction']){
			case 'up':
				$this->Session->setFlash(__('The record was moved up', true));
				if (!$this->{$model}->moveUp($this->{$model}->id, abs(1))) {
					$this->Session->setFlash(__('Unable to move the record up', true));
				}
				break;

			case 'down':
				$this->Session->setFlash(__('The record was moved down', true));
				if (!$this->{$model}->moveDown($this->{$model}->id, abs(1))) {
					$this->Session->setFlash(__('Unable to move the record down', true));
				}
				break;

			default:
				$this->Session->setFlash(__('Error occured reordering the records', true));
				break;
		} // switch
		return true;
	}

	/**
	 * Moving records that actas sequenced
	 *
	 * This is used for moving sequenced records and is called by admin_reorder.
	 */
	function _orderedMove(){
		$model = $this->modelNames[0];

		if (isset($this->$model->actsAs['Libs.Sequence']['order_field']) && !empty($this->$model->actsAs['Libs.Sequence']['order_field'])) {
			$this->data[$model][$this->$model->actsAs['Libs.Sequence']['order_field']] = $this->params['named']['possition'];
		}

		else{
			$this->data[$model]['ordering'] = $this->params['named']['possition'];
		}

		if (!$this->$model->save($this->data)) {
			$this->Session->setFlash(__('The record could not be moved', true));
		}

		return true;
	}

	/**
	* delete records.
	*
	* delete records throughout the app.
	*
	* @todo -c"AppController" Implement AppController.
	* - make a confirm if the js box does not happen. eg open delete in new
	*     window there is no confirm, just delete.
	* - undo thing... maybe save the whole record in the session and if click
	*     undo just save it back, or use soft delete and purge
	* @param mixed $id the id of the record.
	* @return n /a just redirects with different messages in {@see Session::setFlash}
	*/
	function admin_delete($id = null) {
		$model = $this->modelClass;

		if (!$id) {
			$this->Session->setFlash('That ' . $model . ' could not be found', true);
			$this->redirect($this->referer());
		}

		if ($this->$model->delete($id)) {
			$this->Session->setFlash(__('The ' . $model . ' has been deleted', true));
			$this->redirect(array('action' => 'index'));
		}
	}

	function admin_commentPurge($class = null) {
		echo 'moved to comments';
	}

	/**
	 * Method to handle mass actions (Such as mass deletions, toggles, etc.)
	 *
	 * @return mixed
	 */
	function admin_mass() {
		$massAction = $this->MassAction->getAction($this->params['form']);
		$ids = $this->MassAction->getIds(
			$massAction,
			$this->data[isset($this->data['Confirm']['model']) ? $this->data['Confirm']['model'] : $this->modelClass]
		);

		$massActionMethod = '__massAction' . ucfirst($massAction);

		if(method_exists($this, $massActionMethod)){
			return $this->{$massActionMethod}($ids);
		}
		elseif(method_exists($this->MassAction, $massAction)) {
			return $this->MassAction->{$massAction}($ids);
		}
		else {
			return $this->MassAction->generic($massAction, $ids);
		}
	}

	/**
	* Create ACO's automaticaly
	*
	* http://book.cakephp.org/view/647/An-Automated-tool-for-creating-ACOs
	*/
	function admin_buildAcl() {
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id;
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}

		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'buildAcl';

		$Plugins = $this->_getPlugins();

		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach ((array)$methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
	}

	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (is_array($properties) && array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			}
			/*else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}*/
		}

		return $methods;
	}

	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}

	function _getPlugins(){
		$plugins = array(
			'infinitas',
			'extentions',
			'plugins'
		);
		$return = array();
		foreach($plugins as $plugin ){
			$return = array_merge($return, $this->_getPluginControllerNames($plugin));
		}

		return $return;
	}

	/**
	 * Get the names of the plugin controllers ...
	 *
	 * This function will get an array of the plugin controller names, and
	 * also makes sure the controllers are available for us to get the
	 * method names by doing an App::import for each plugin controller.
	 *
	 * @return array of plugin names.
	 *
	 */
	function _getPluginControllerNames($plugin) {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . $plugin);

		$Plugins = $folder->read();
		$Plugins = $Plugins[0];

		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . $plugin. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}

	function admin_getPlugins(){
		$this->set('json', $this->{$this->modelClass}->getPlugins());
	}

	function admin_getControllers(){
		$plugin = $this->params['named']['plugin'];

		$list = App::objects(
			'controller',
			array(App::pluginPath($plugin).'controllers'.DS),
			false
		);

		foreach($list as $controller){
			$controllers[$controller] = $controller;
		}

		$this->set('json', $controllers);
	}

	function admin_getActions(){
		$plugin = $this->params['named']['plugin'];
		$controller = $this->params['named']['controller'];

		App::import('Controller', $plugin.'.'.$controller);

		$list = get_class_methods($controller.'Controller');
		$ignore = $this->_methodsToIgnore();

		$actions = array();
		foreach((array)$list as $action){
			if (in_array($action, $ignore) || substr($action, 0, 1) == '_'){
				continue;
			}
			else{
				$actions[$action] = $action;
			}
		}

		$this->set('json', $actions);
	}

	function _methodsToIgnore(){
		$return = get_class_methods('AppController');
		return $return;
	}
}
?>