<?php
	/**
	 * @mainpage Infinitas - CakePHP powered Content Management Framework
	 *
	 * @section infinitas-overview What is it
	 *
	 * Infinitas is a content management framework that allows you to create powerful
	 * application using the CakePHP framework in the fastes way posible. It follows
	 * the same convention over configuration design paradigm. All the coding standards
	 * of CakePHP are followed, and the core libs are used as much as possible to
	 * limit the amount of time that is required to get a hang of what is going on.
	 *
	 * Infinitas aims to take care of all the normal things that most sites require
	 * so that you can spend time building the application instead. Things like
	 * comments, users, auth, geo location, emailing and view counting is built into
	 * the core.
	 *
	 * There is a powerful Event system that makes plugins truly seperate from
	 * the core, so seperate that they can be disabled from the backend and its like
	 * the plugin does not exist.
	 *
	 * The bulk of work that has been done to Infinitas has been to the admin
	 * backend and internal libs. The final product is something that is extreamly
	 * easy to extend, but also very usable. Knowing that one day you will need to
	 * hand the project over to a client that may not be to technical has always been
	 * one of the main considerations.
	 *
	 * Infinitas is here to bridge the gap between usablity and extendability offering
	 * the best of both worlds, something that developers can build upon and end users
	 * can actually use.
	 *
	 * @section categories-usage How to use it
	 *
	 * To get started check out the installation guide, currently there is only
	 * a web based installer but shortly we will have some shell commands for
	 * the people that are not fond of icons.
	 *
	 * You may also want to check the feature list and versions to get an overview
	 * of what the project has to offer.
	 */

	/**
	 * @brief global methods so the AppController is a bit cleaner.
	 *
	 * basicaly all the methods like _something should be moved to a component
	 *
	 * @todo this needs to be more extendable, something like the ChartsHelper
	 * could work.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	App::uses('InfinitasComponent', 'Libs.Controller/Component');
	App::uses('InfinitasHelper', 'Libs.View/Helper');
	
	class GlobalActions extends Controller {
		/**
		 * components should not be included here
		 *
		 * @var array
		 * @access public
		 */
		public $components = array();

		/**
		 * reference to the model name of the current controller
		 *
		 * @var string
		 * @access public
		 */
		public $modelName;

		/**
		 * reference to the model name for user output
		 *
		 * @var string
		 * @access public
		 */
		public $prettyModelName;

		/**
		 * @brief defaults for AppController::notice()
		 * @var array
		 */
		public $notice = array(
			'saved' => array(
				'message' => 'Your %s was saved',
				'redirect' => ''
			),
			'not_saved' => array(
				'message' => 'There was a problem saving your %s',
				'level' => 'warning'
			),
			'invalid' => array(
				'message' => 'Invalid %s selected, please try again',
				'level' => 'error',
				'redirect' => true
			),
			'deleted' => array(
				'message' => 'Your %s was deleted',
				'redirect' => true
			),
			'not_deleted' => array(
				'message' => 'Your %s was not deleted',
				'level' => 'error',
				'redirect' => true
			),
			'disabled' => array(
				'message' => 'That action has been disabled',
				'level' => 'error',
				'redirect' => true
			),
			'auth' => null
		);

		/**
		 * @brief Construct the Controller
		 *
		 * Currently getting components that are needed by the application. they
		 * are then loaded into $components making them available to the entire
		 * application.
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function __construct($request = null, $response = null) {
			$this->__setupConfig();
			$event = EventCore::trigger($this, 'requireComponentsToLoad');

			if(isset($event['requireComponentsToLoad']['libs'])){
				$libs['libs'] = $event['requireComponentsToLoad']['libs'];
				$event['requireComponentsToLoad'] = $libs + $event['requireComponentsToLoad'];
			}

			foreach($event['requireComponentsToLoad'] as $plugin => $components){
				if(!empty($components)){
					if(!is_array($components)){
						$components = array($components);
					}
					$this->components = array_merge((array)$this->components, (array)$components);
				}
			}

			parent::__construct($request, $response);
		}

		/**
		 * @brief Set up some general variables that are used around the code.
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			
			$this->modelClass = $this->modelClass;
			$this->prettyModelName = prettyName($this->modelClass);
			if(!$this->Session->read('ip_address')){
				$this->Session->write('ip_address', $this->RequestHandler->getClientIp());
			}
			
			$modelName = !empty($this->prettyModelName) ? $this->prettyModelName : prettyName($this->name);
			$modelName = Inflector::singularize($modelName);
			foreach($this->notice as $type => &$config) {
				if(empty($config['message'])) {
					continue;
				}

				if(strstr($config['message'], '%s')) {
					$plugin = Inflector::underscore($this->plugin);
					$config['message'] = __d($plugin, $config['message'], $modelName);
				}
			}

			return true;
		}

		/**
		 * @brief Set up system configuration.
		 *
		 * Load the default configuration and check if there are any configs
		 * to load from the current plugin. configurations can be completely rewriten
		 * or just added to.
		 *
		 * @access private
		 *
		 * @return void
		 */
		private function __setupConfig(){
			$configs = ClassRegistry::init('Configs.Config')->getConfig();

			$eventData = EventCore::trigger($this, $this->plugin.'.setupConfigStart', $configs);
			if (isset($eventData['setupConfigStart'][$this->plugin])){
				$configs = (array)$eventData['setupConfigStart'][$this->plugin];

				if (!array($configs)) {
					$this->cakeError('eventError', array('message' => 'Your config is wrong.', 'event' => $eventData));
				}
			}

			$eventData = EventCore::trigger($this, $this->plugin.'.setupConfigEnd');
			if (isset($eventData['setupConfigEnd'][$this->plugin])){
				$configs = $configs + (array)$eventData['setupConfigEnd'][$this->plugin];
			}

			if (!$this->__writeConfigs($configs)) {
				$this->cakeError('configError', array('message' => 'Config was not written'));
			}

			unset($configs, $eventData);
		}

		/**
		 * Write the configuration.
		 *
		 * Write all the config values that have been called found in InfinitasComponent::setupConfig()
		 *
		 * @access private
		 *
		 * @return bool
		 */
		private function __writeConfigs($configs){
			foreach($configs as $config) {
				if(empty($config) || !is_array($config)){
					continue;
				}

				if (!(isset($config['Config']['key']) || isset($config['Config']['value']))) {
					$config['Config']['key'] = isset($config['Config']['key']) ? $config['Config']['key'] : 'NOT SET';
					$config['Config']['value'] = isset($config['Config']['value']) ? $config['Config']['value'] : 'NOT SET';
					$this->log(serialize($config['Config']), 'configuration_error');
					continue;
				}

				Configure::write($config['Config']['key'], $config['Config']['value']);
			}

			unset($configs);
			return true;
		}

		/**
		 * @brief allow posting comments to any controller
		 *
		 * @todo this needs to be moved to the Comments plugin and is part of
		 * the reason this code needs to be more extendable
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function comment($id = null) {
			if (!empty($this->request->data[$this->modelClass.'Comment'])) {
				$message = 'Your comment has been saved and will be available after admin moderation.';
				if (Configure::read('Comments.auto_moderate') === true) {
					$message = 'Your comment has been saved and is active.';
				}

				$this->request->data[$this->modelClass.'Comment']['class'] = Inflector::camelize($this->request->params['plugin']).'.'.$this->modelClass;

				if(!empty($this->request->data[$this->modelClass.'Comment']['om_non_nom'])) {
					$this->Session->write('Spam.bot', true);
					$this->Session->write('Spam.detected', time());

					$this->notice(
						__d('comments', 'Not so fast spam bot.'),
						array(
							'redirect' => '/?bot=true'
						)
					);
				}

				else if ($this->{$this->modelClass}->createComment($this->request->data)) {
					$this->notice(
						__d('comments', $message),
						array('redirect' => true)
					);
				}
				
				else {
					$this->notice('not_saved');
				}
			}

			$this->render(null, null, App::pluginPath('Comment') . 'View' . DS . 'InfinitasComments' . DS . 'add.ctp');
		}

		/**
		 * @brief preview pages from admin when they are inactive
		 *
		 * method for admin to preview items without having them active, this
		 * expects a few things from the code being previewed.
		 *
		 * you need a model method called getViewData for the view page that takes a conditions array
		 * you should have a file named view.ctp
		 * only admin can access this page
		 * the page will be passed a variable named with Inflector::variable()
		 *
		 * @param mixed $id id of the record
		 * @access public
		 *
		 * @return void
		 */
		public function preview($id = null){
			if(!$id || (int)$this->Session->read('Auth.User.group_id') !== 1){
				$this->notice(
					__('That page does not exist'),
					array(
						'redirect' => true
					)
				);
			}

			if(!is_callable(array($this->{$this->modelClass}, 'getViewData'))){
				$this->notice(
					__('There is no preview available'),
					array(
						'level' => 'error',
						'redirect' => Router::url('/')
					)
				);
			}

			$varName = Inflector::variable($this->modelClass);

			$$varName = $this->{$this->modelClass}->getViewData(
				array(
					$this->modelClass . '.' . $this->{$this->modelClass}->primaryKey => $id
				)
			);

			$this->set($varName, $$varName);
			$this->render('view');
		}

		/**
		 * @brief Common method for rating.
		 *
		 * This is the default method for a rating, if you would like to change
		 * the way it works for your own plugin just define your own method in the
		 * plugins app_controller or the actual controller.
		 *
		 * By default it will check if users need to be logged in before rating and
		 * redirect if they must and are not. else it will get the ip address and then
		 * save the rating.
		 *
		 * @todo check if the model is a rateable model.
		 * @todo needs to go on a diet, moved to a rating plugin
		 *
		 * @param int $id the id of the itme you are rating.
		 * @access public
		 *
		 * @return null, will redirect.
		 */
		public function rate($id = null) {
			$this->request->data['Rating']['ip'] = $this->RequestHandler->getClientIP();
			$this->request->data['Rating']['user_id'] = $this->Session->read('Auth.User.id');
			$tempClass = ucfirst($this->request->params['plugin']) . '.' . $this->modelClass;
			$this->request->data['Rating']['class'] = isset($this->request->data['Rating']['class']) ? $this->request->data['Rating']['class'] : $tempClass;
			$this->request->data['Rating']['foreign_id'] = isset($this->request->data['Rating']['foreign_id']) ? $this->request->data['Rating']['foreign_id'] : $id;
			$this->request->data['Rating']['rating'] = isset($this->request->data['Rating']['rating']) ? $this->request->data['Rating']['rating'] : $this->request->params['named']['rating'];

			$this->log(serialize($this->request->data['Rating']));

			if (Configure::read('Rating.require_auth') === true && !$this->request->data['Rating']['user_id']) {
				$this->notice(
					__('You need to be logged in to rate this item'),
					array(
						'redirect' => array(
							'plugin' => 'Users',
							'controller' => 'Users',
							'login'
						)
					)
				);
			}

			if (!empty($this->request->data['Rating']['rating'])) {
				$message = __('It seems you have already voted for this item.');

				if ($this->{$this->modelClass}->rateRecord($this->request->data)) {
					$data = $this->{$this->modelClass}->find(
						'first',
						array(
							'fields' => array(
								$this->modelClass.'.rating',
								$this->modelClass.'.rating_count'
							),
							'conditions' => array(
								$this->modelClass.'.id' => $this->request->data['Rating']['foreign_id']
							)
						)
					);
					$message = sprintf(__('Saved! new rating %s (out of %s)'), $data[$this->modelClass]['rating'], $data[$this->modelClass]['rating_count']);
				}

				if($this->RequestHandler->isAjax()){
					Configure::write('debug', 0);
					$this->set('json', array('message' => $message));
				}

				$this->notice(
					$message,
					array(
						'redirect' => true
					)
				);
			}
		}

		/**
		 * Some global methods for admin
		 */
		/**
		 * @brief get a list of all the plugins in the app
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function admin_getPlugins(){
			$this->set('json', $this->{$this->modelClass}->getPlugins());
		}

		/**
		 * @brief get a list of all the controllers for the selected plugin
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function admin_getControllers(){
			if (!isset($this->request->data[$this->modelClass]['plugin'])) {
				$this->set('json', array('error'));
				return;
			}

			$this->set('json', $this->{$this->modelClass}->getControllers($this->request->data[$this->modelClass]['plugin']));
		}

		/**
		 * @brief get a list of all the models for the selected plugin
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function admin_getModels(){
			if (!isset($this->request->data[$this->modelClass]['plugin'])) {
				$this->set('json', array('error'));
				return;
			}
			
			$this->set('json', $this->{$this->modelClass}->getModels($this->request->data[$this->modelClass]['plugin']));
		}

		/**
		 * @brief get a list of all the actions for the selected plugin + controller
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function admin_getActions(){
			if (!(isset($this->request->data[$this->modelClass]['plugin']) && isset($this->request->data[$this->modelClass]['controller'] ))) {
				$this->set('json', array('error'));
				return;
			}
			$this->set(
				'json',
				$this->{$this->modelClass}->getActions(
					$this->request->data[$this->modelClass]['plugin'],
					$this->request->data[$this->modelClass]['controller']
				)
			);
		}

		/**
		 * Create ACO's automaticaly
		 *
		 * http://book.cakephp.org/view/647/An-Automated-tool-for-creating-ACOs
		 *
		 * @deprecated
		 */
		public function admin_buildAcl() {
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
			}

			else {
				$root = $root[0];
			}

			App::import('Core', 'File');
			$Controllers = Configure::listObjects('controller');
			$appIndex = array_search('App', $Controllers);
			if ($appIndex !== false ) {
				unset($Controllers[$appIndex]);
			}
			$baseMethods = get_class_methods('Controller');
			$baseMethods[] = 'admin_buildAcl';
			$baseMethods[] = 'blackHole';
			$baseMethods[] = 'comment';
			$baseMethods[] = 'rate';
			$baseMethods[] = 'blackHole';
			$baseMethods[] = 'addCss';
			$baseMethods[] = 'addJs';
			$baseMethods[] = 'admin_delete';

			$Plugins = $this->Infinitas->_getPlugins();

			$Controllers = array_merge($Controllers, $Plugins);

			// look at each controller in app/controllers
			foreach ($Controllers as $ctrlName) {
				$methods = $this->Infinitas->_getClassMethods($this->Infinitas->_getPluginControllerPath($ctrlName));

				// Do all Plugins First
				if ($this->Infinitas->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/'.$this->Infinitas->_getPluginName($ctrlName));
					if (!$pluginNode) {
						$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->Infinitas->_getPluginName($ctrlName)));
						$pluginNode = $aco->save();
						$pluginNode['Aco']['id'] = $aco->id;
						$log[] = 'Created Aco node for ' . $this->Infinitas->_getPluginName($ctrlName) . ' Plugin';
					}
				}
				// find / make controller node
				$controllerNode = $aco->node('controllers/'.$ctrlName);
				if (!$controllerNode) {
					if ($this->Infinitas->_isPlugin($ctrlName)){
						$pluginNode = $aco->node('controllers/' . $this->Infinitas->_getPluginName($ctrlName));
						$aco->create(
							array(
								'parent_id' => $pluginNode['0']['Aco']['id'],
								'model' => null,
								'alias' => $this->Infinitas->_getPluginControllerName($ctrlName)
							)
						);
						$controllerNode = $aco->save();
						$controllerNode['Aco']['id'] = $aco->id;
						$log[] = 'Created Aco node for ' . $this->Infinitas->_getPluginControllerName($ctrlName) . ' ' .
							$this->Infinitas->_getPluginName($ctrlName) . ' Plugin Controller';
					}

					else {
						$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
						$controllerNode = $aco->save();
						$controllerNode['Aco']['id'] = $aco->id;
						$log[] = 'Created Aco node for ' . $ctrlName;
					}
				}

				else {
					$controllerNode = $controllerNode[0];
				}

				//clean the methods. to remove those in Controller and private actions.
				foreach ((array)$methods as $k => $method) {
					if (strpos($method, '_', 0) === 0 || in_array($method, $baseMethods)) {
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


		/**
		 * Mass actions
		 *
		 * Method to handle mass actions (Such as mass deletions, toggles, etc.)
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function admin_mass() {
			$massAction = $this->MassAction->getAction();
			$modelName = isset($this->request->data['Confirm']['model']) ? $this->request->data['Confirm']['model'] : $this->modelClass;
			$ids = $this->MassAction->getIds(
				$massAction,
				$this->request->data[$modelName]
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
		 * @brief prg method to show the users documents
		 *
		 * redirects to the filtered url for the users own records
		 */
		public function admin_mine(){
			if(!$this->{$this->modelClass}->hasField('user_id')){
				$this->notice(
					__('Cant determin a user field'),
					array(
						'redirect' => true,
						'level' => 'error'
					)
				);
			}

			if(!$this->Auth->user('id')){
				$this->notice(
					__('You need to be logged in to do that'),
					array(
						'redirect' => true,
						'level' => 'error'
					)
				);
			}

			$this->redirect(
				array(
					'action' => 'index',
					$this->{$this->modelClass}->alias . '.user_id' => $this->Auth->user('id')
				)
			);
		}

		/**
		 * @brief Simple Admin add method.
		 *
		 * If you need simple Add method for your admin just dont create one and
		 * it will fall back to this. It does the basics, saveAll with a
		 * Session::setFlash() message.
		 *
		 * @todo sanitize input
		 * @todo render generic view
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function admin_add(){
			if (!empty($this->request->data)) {
				$this->{$this->modelClass}->create();
				if ($this->{$this->modelClass}->saveAll($this->request->data)) {
					$this->notice('saved');
				}
				$this->notice('not_saved');
			}

			$this->saveRedirectMarker();
		}

		/**
		 * @brief Simple Admin edit method
		 *
		 * If you need simple Edit method for your admin just dont create one and
		 * it will fall back to this. It does the basics, saveAll with a
		 * Session::setFlash() message.
		 *
		 * @todo sanitize input
		 * @todo render generic view
		 *
		 * @param mixed $id int | string (uuid) the id of the record to edit.
		 * @access public
		 *
		 * @return void
		 */
		public function admin_edit($id = null, $query = array()) {
			if(empty($this->request->data) && !$id){
				$this->notice('invalid');
			}

			if (!empty($this->request->data)) {
				if ($this->{$this->modelClass}->saveAll($this->request->data)) {
					$this->notice('saved');
				}
				$this->notice('not_saved');
			}

			if(empty($this->request->data) && $id){
				$query['conditions'][$this->{$this->modelClass}->alias . '.' . $this->{$this->modelClass}->primaryKey] = $id;

				$this->request->data = $this->{$this->modelClass}->find('first', $query);
				if(empty($this->request->data)){
					$this-->notice('invalid');
				}
			}

			$this->saveRedirectMarker();
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
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function admin_reorder($id = null) {
			$model = $this->modelClass;

			if (!$id) {
				$this->notice('invalid');
			}

			$this->request->data[$model]['id'] = $id;

			if (!isset($this->request->params['named']['position']) && isset($this->$model->actsAs['Libs.Sequence'])) {
				$this->notice(
					__('A problem occured moving the ordered record.'),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}

			if (!isset($this->request->params['named']['direction']) && isset($this->$model->actsAs['Tree'])) {
				$this->notice(
					__('A problem occured moving that MPTT record.'),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}

			if (isset($this->request->params['named']['position'])) {
				$this->Infinitas->orderedMove();
			}

			if (isset($this->request->params['named']['direction'])) {
				$this->Infinitas->treeMove();
			}

			$this->redirect($this->referer());
		}
	}

	/**
	 * @page AppController AppController
	 *
	 * @section app_controller-overview What is it
	 *
	 * AppController is the main controller method that all other countrollers
	 * should normally extend. This gives you a lot of power through inheritance
	 * allowing things like mass deletion, copying, moving and editing with absolutly
	 * no code.
	 *
	 * AppController also does a lot of basic configuration for the application
	 * to run like automatically putting components in to load, compressing output
	 * setting up some security and more.
	 *
	 * @section app_controller-usage How to use it
	 *
	 * Usage is simple, extend your MyPluginAppController from this class and then the
	 * controllers in your plugin just extend MyPluginAppController. Example below:
	 *
	 * @code
	 *	// in APP/plugins/my_plugin/my_plugin_app_controller.php create
	 *	class MyPluginAppController extends AppModel{
	 *		// do not set the name in this controller class, there be gremlins
	 *	}
	 *
	 *	// then in APP/plugins/my_plugin/controllers/something.php
	 *	class SomethingsController extends MyPluginAppController{
	 *		public $name = 'Somethings';
	 *		//...
	 *	}
	 * @endcode
	 *
	 * After that you will be able to directly access the public methods that
	 * are available from this class as if they were in your controller.
	 *
	 * @code
	 *	$this->someMethod();
	 * @endcode
	 *
	 * @section app_controller-see-also Also see
	 * @ref GlobalActions
	 * @ref InfinitasComponent
	 * @ref Event
	 * @ref MassActionComponent
	 * @ref InfinitasView
	 */

	/**
	 * @brief AppController is the main controller class that all plugins should extend
	 *
	 * This class offers a lot of methods that should be inherited to other controllers
	 * as it is what allows you to build plugins with minimal code.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	
	class AppController extends GlobalActions {		
		/**
		 * the View Class that will load by defaul is the Infinitas View to take
		 * advantage which extends the ThemeView and auto loads the Mustache class.
		 * This changes when requests are json etc
		 */
		public $viewClass = 'Libs.Infinitas';

		/**
		 * internal cache of css files to load
		 *
		 * @var array
		 * @access private
		 */
		private $__addCss = array();

		/**
		 * internal cache of javascript files to load
		 *
		 * @var array
		 * @access private
		 */
		private $__addJs  = array();

		private $__callBacks = array(
			'beforeFilter' => false,
			'beforeRender' => false,
			'afterFilter' => false
		);

		/**
		 * @brief called before a page is loaded
		 * 
		 * before render is called before the page is rendered, but after all the
		 * processing is done.
		 *
		 * @link http://api.cakephp.org/class/controller#method-ControllerbeforeRender
		 *
		 * @todo this could be moved to the InfinitasView class
		 */
		public function beforeRender(){
			parent::beforeRender();

			switch(true){
				case (!empty($this->request->params['ext']) && $this->request->params['ext'] == 'json'):
					$this->viewVars['json'] = array('json' => $this->viewVars['json']);
					$this->set('_serialize', 'json');
					//Configure::write('debug', 0);
					break;

				case $this->request->is('ajax'):

					break;

				case $this->RequestHandler->prefers('rss'):
					;
					break;

				case $this->RequestHandler->prefers('vcf'):
					;
					break;
			}
			
			$this->Infinitas->getPluginAssets();		
			$this->set('css_for_layout', array_filter($this->__addCss));
			$this->set('js_for_layout', array_filter($this->__addJs));

			$fields = array(
				$this->request->params['plugin'],
				$this->request->params['controller'],
				$this->request->params['action']
			);

			$this->set('class_name_for_layout', implode(' ', $fields));
			unset($fields);

			$this->__callBacks[__FUNCTION__] = true;
		}

		/**
		 * @brief redirect pages
		 * 
		 * Redirect method, will remove the last_page session var that is stored
		 * when adding/editing things in admin. makes redirect() default to /index
		 * if there is no last page.
		 * 
		 * @access public
		 *
		 * @link http://api.cakephp.org/class/controller#method-Controllerredirect
		 * 
		 * @param mixed $url string or array url
		 * @param int $status the code for the redirect 301 / 302 etc
		 * @param bool $exit should the script exit after the redirect
		 *
		 * @return void
		 */
		public function redirect($url = null, $status = null, $exit = true) {
			if(!$url || $url == ''){
				$url = $this->getPageRedirectVar();
				
				if(!$url){
					$url = array('action' => 'index');
				}
			}
			
			parent::redirect($url, $status, $exit);
		}
		
		/**
		 * @brief get the variable for the last page saved.
		 * 
		 * Making this a bit more dry so that its less error prone
		 * 
		 * @access public
		 * 
		 * @return string the session key used for lookups
		 */
		public function lastPageRedirectVar() {
			return 'Infinitas.last_page.' . $this->request->here;
		}
		
		/**
		 * @brief save a maker for a later redirect
		 * 
		 * @access public
		 * 
		 * This will set a session var for the current page
		 * 
		 * @return void
		 */
		public function saveRedirectMarker() {
			$var = $this->lastPageRedirectVar();
			
			$lastPage = $this->Session->read($var);
			if(!$lastPage){
				$this->Session->write($var, $this->referer());
			}
		}
		
		/**
		 * @brief get the page redirect value
		 * 
		 * This will get the correct place to redirect to if there is a value
		 * saved for the current location, if there is nothing false is returned
		 * 
		 * @param bool $delete should the value be removed from session
		 * 
		 * @return mixed, false for nothing, string url if available
		 */
		public function getPageRedirectVar($delete = true) {
			$var = $this->lastPageRedirectVar();
			
			$url = false;
			if($this->Session->check($var)) {
				$url = $this->Session->read($var);
				
				if($delete === true) {
					$this->Session->delete($var);
				}
			}
			
			return $url;
		}

		/**
		 * @brief normal before filter.
		 *
		 * set up some variables and do a bit of pre processing before handing
		 * over to the controller responsible for the request.
		 *
		 * @link http://api.cakephp.org/class/controller#method-ControllerbeforeFilter
		 * 
		 * @access public
		 *
		 * @return void
		 */
		public function beforeFilter() {
			parent::beforeFilter();

			// @todo it meio upload is updated.
			if(isset($this->request->data['Image']['image']['name']) && empty($this->request->data['Image']['image']['name'])){
				unset($this->request->data['Image']);
			}

			$this->request->params['admin'] = isset($this->request->params['admin']) ? $this->request->params['admin'] : false;

			if($this->request->params['admin'] && $this->request->params['action'] != 'admin_login' && $this->Session->read('Auth.User.group_id') != 1){
				$this->redirect(array('admin' => 1, 'plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
			}

			if (isset($this->request->data['PaginationOptions']['pagination_limit'])) {
				$this->Infinitas->changePaginationLimit( $this->request->data['PaginationOptions'], $this->request->params );
			}

			if (isset($this->request->params['named']['limit'])) {
				$this->request->params['named']['limit'] = $this->Infinitas->paginationHardLimit($this->request->params['named']['limit']);
			}

			if(isset($this->request->params['form']['action']) && $this->request->params['form']['action'] == 'cancel'){
				if(isset($this->{$this->modelClass}) && $this->{$this->modelClass}->hasField('locked') && isset($this->request->data[$this->modelClass]['id'])){
					$this->{$this->modelClass}->unlock($this->request->data[$this->modelClass]['id']);
				}
				$this->redirect(array('action' => 'index'));
			}
			
			if(!empty($this->request->data[$this->modelClass]['om_nom_nom'])) {
				$this->Session->write('Spam.bot', true);
				$this->Session->write('Spam.detected', time());
			}
			
			else if($this->Session->read('Spam.bot')) {
				if((time() - 3600) > $this->Session->read('Spam.detected')) {
					$this->Session->write('Spam', null);
				}
			}

			if (sizeof($this->uses) && (isset($this->{$this->modelClass}->Behaviors) && $this->{$this->modelClass}->Behaviors->attached('Logable'))) {
				$this->{$this->modelClass}->setUserData($this->Session->read('Auth'));
			}

			$this->__callBacks[__FUNCTION__] = true;
		}

		/**
		 * @brief add css from other controllers.
		 *
		 * way to inject css from plugins to the layout. call addCss(false) to
		 * clear current stack, call addCss(true) to get a list back of what is there.
		 *
		 * @param mixed $css array of paths like HtmlHelper::css or a string path
		 * @access public
		 *
		 * @return mixed bool for adding/removing or array when requesting data
		 */
		public function addCss($css = false){
			return $this->__loadAsset($css, __FUNCTION__);
		}

		/**
		 * @brief add js from other controllers.
		 *
		 * way to inject js from plugins to the layout. call addJs(false) to
		 * clear current stack, call addJs(true) to get a list back of what is there.
		 *
		 * @param mixed $js array of paths like HtmlHelper::css or a string path
		 * @access public
		 *
		 * @return mixed bool for adding/removing or array when requesting data
		 */
		public function addJs($js = false){
			return $this->__loadAsset($js, __FUNCTION__);
		}

		/**
		 * @brief DRY method for AppController::addCss() and AppController::addJs()
		 *
		 * loads the assets into a var that will be sent to the view, used by
		 * addCss / addJs. if false is passed in the var is reset, if true is passed
		 * in you get back what is currently set.
		 *
		 * @param mixed $data takes bool for reseting, strings and arrays for adding
		 * @param string $method where its going to store / remove
		 * @access private
		 * 
		 * @return mixed true on success, arry if you pass true
		 */
		private function __loadAsset($data, $method){
			$property = '__' . $method;
			if($data === false){
				$this->{$property} = array();
				return true;
			}

			else if($data === true){
				return $this->{$property};
			}

			foreach((array)$data as $_data){
				if(is_array($_data)){
					$this->{$method}($_data);
					continue;
				}

				if(!in_array($_data, $this->{$property}) && !empty($_data)){
					$this->{$property}[] = $_data;
				}
			}

			return true;
		}

		/**
		 * @brief render method
		 *
		 * Infinits uses this method to use admin_form.ctp for add and edit views
		 * when there is no admin_add / admin_edit files available.
		 * 
		 * @access public
		 *
		 * @link http://api.cakephp.org/class/controller#method-Controllerrender
		 *
		 * @param string $view View to use for rendering
		 * @param string $layout Layout to use
		 *
		 * @return Full output string of view contents
		 */
		public function render($view = null, $layout = null) {
			if(($this->request->action == 'admin_edit' || $this->request->action == 'admin_add')) {
				$viewPath = App::pluginPath($this->plugin) . 'View' . DS . $this->viewPath . DS . $this->request->action . '.ctp';
				if(!file_exists($viewPath)) {
					$view = 'admin_form';
				}
			}

			else if(($this->request->action == 'edit' || $this->request->action == 'add')) {
				$viewPath = App::pluginPath($this->plugin) . 'View' . DS . $this->viewPath . DS . $this->request->action . '.ctp';
				if(!file_exists($viewPath)) {
					$view = 'form';
				}
			}

			return parent::render($view, $layout);
		}

		/**
		 * @brief blackHole callback for security component
		 * 
		 * this function is just here to stop wsod confusion. it will become more
		 * usefull one day
		 *
		 * @todo maybe add some emailing in here to notify admin when requests are
		 * being black holed.
		 *
		 * @link http://api.cakephp.org/view_source/security-component/#l-427
		 *
		 * @param object $Controller the controller object that triggered the blackHole
		 * @param string $error the error message
		 * @access public
		 * 
		 * @return it ends the script
		 */
		public function blackHole($Controller) {
			var_dump($Controller);
			pr('you been blackHoled');
			exit;
		}

		/**
		 * @brief after all processing is done and the page is ready to show
		 * 
		 * after filter is called after your html is put together, and just before
		 * it is rendered to the user. here we are removing any white space from
		 * the html before its output.
		 * 
		 * @access public
		 *
		 * @link http://api.cakephp.org/class/controller#method-ControllerafterFilter
		 */
		public function afterFilter(){
			if(Configure::read('debug') === 0 && $this->request->params['url']['ext'] == 'html'){
				$this->output = preg_replace(
					array(
						'/ {2,}/',
						'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
					),
					array(
						' ',
						''
					),
					$this->output
				);
			}

			$this->__callBacks[__FUNCTION__] = true;
		}

		/**
		 * @brief make sure everything is running or throw an error
		 */
		public function __destruct() {
			$check = array_unique($this->__callBacks);
			
			if(count($check) != 1 || current($check) != true) {
				user_error('Some callbacks were not triggered, check for methods returning false', E_USER_NOTICE);
			}
		}

		/**
		 * @brief Create a generic warning to display usefull information to the user
		 *
		 * The method can be used in two ways, using the $this->notice param and setting
		 * up some defaults or direclty passing the message and config.
		 *
		 * @code
		 *	// manual
		 *	$this->notice(__d('plugin', 'foo bar'), array('redirect' => true, 'level' => 'warning'));
		 *
		 *	// pre set
		 *	$this->notice['my_message'] = array(
		 *		'message' => 'foo bar',
		 *		'redirect' => true, // false, '', array() '/url'
		 *		'level' => 'warning', // success, error etc
		 *	);
		 *
		 *	$this->notice('my_message');
		 *
		 *	// custom pre set uses config from ->notice['my_message'] but will
		 *	// have level of success
		 *	$this->notice('my_message', array('level' => 'success'));
		 * @endcode
		 *
		 * Infintias sets up a number of defaults for notices including saved, not_saved,
		 * invalid, deleted, not_deleted, disabled, auth. See $this->notice for more
		 * on what they are.
		 *
		 * You can overwrite the defaults by creating them in your __construct() or any time
		 * before calling Controller::notice().
		 * 
		 * The code passed can be used for linking to error pages with more information
		 * eg: creating some pages on your site like /errors/<code> and then making it
		 * a clickable link the user can get more detailed information.
		 *
		 * @access public
		 *
		 * @param string $message the message to show to the user
		 * @param array $config array of options for the redirect and message
		 * 
		 * @return string the markup for the error
		 */
		public function notice($message, $config = array()) {
			$_default = array(
				'level' => 'success',
				'code' => 0,
				'plugin' => 'assets',
				'redirect' => false
			);

			if(!empty($this->notice[$message])) {
				if(!is_array($this->notice[$message])) {
					$message = $this->notice[$message];
				}
				
				else if(!empty($this->notice[$message]['message'])) {
					$config = array_merge($this->notice[$message], $config);
					$message = $config['message'];
					unset($config['message']);
				}
			}

			$config = array_merge($_default, (array)$config);

			$vars = array(
				'code' => $config['code'],
				'plugin' => $config['plugin']
			);
			
			$this->Session->setFlash($message, 'messages/'.$config['level'], $vars);
			if($config['redirect'] || $config['redirect'] === ''){
				if($config['redirect'] === true){
					$config['redirect'] = $this->referer();
				}
				$this->redirect($config['redirect']);
			}
			
			unset($_default, $config, $vars);
		}
	}

	EventCore::trigger(new stdClass(), 'loadAppController');
