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
	class AppController extends GlobalActions {
		public $name = 'AppController';
		
		/**
		*
		*/
		public $view = 'Theme';

		public $components = array(
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
			'Events.Event',
			'Newsletter.Emailer',
			'Facebook.Connect'
		);

		/**
		* actions where viewable will work.
		*/
		public $viewableActions = array(
			'view'
		);

		private $__addCss = array( 
			'/libs/css/jquery_ui'
		);

		private $__addJs  = array( 
			'/libs/js/3rd/jquery',
			'/libs/js/3rd/require',
			'/libs/js/infinitas'
		);
		
		function afterRender(){
			parent::afterRender();
		}

		function beforeRender(){
			parent::beforeRender();
			$this->Infinitas->getPluginAssets();
			$this->Infinitas->getPluginHelpers();			
			$this->set('css_for_layout', array_filter($this->__addCss));
			$this->set('js_for_layout', array_filter($this->__addJs));
		}

		/**
		* normal before filter.
		*/
		function beforeFilter() {
			parent::beforeFilter();

			// @todo it meio upload is updated.
			if(isset($this->data['Image']['image']['name']) && empty($this->data['Image']['image']['name'])){
				unset($this->data['Image']);
			}

			$this->Infinitas->_setupAuth();
			$this->Infinitas->_setupSecurity();
			$this->Infinitas->_setupJavascript();
			if(isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
				$this->addCss(
					array(
						'admin'
					)
				);

				if($this->params['controller'] !== 'upgrade'){
					$this->Infinitas->checkDbVersion();
				}
			}

			if((isset($this->params['admin']) && $this->params['admin']) && $this->params['action'] != 'admin_login' && $this->Session->read('Auth.User.group_id') != 1){
				$this->redirect(array('admin' => 1, 'plugin' => 'management', 'controller' => 'users', 'action' => 'login'));
			}

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

			if (sizeof($this->uses) && (isset($this->{$this->modelClass}->Behaviors) && $this->{$this->modelClass}->Behaviors->attached('Logable'))) {
				$this->{$this->modelClass}->setUserData($this->Session->read('Auth'));
			}

			if(isset($this->RequestHandler)){
				switch(true){
					case $this->RequestHandler->prefers('json'):
						$this->view = 'Libs.Json';
						Configure::write('debug', 0);
						break;

					case $this->RequestHandler->prefers('rss'):
						;
						break;

					case $this->RequestHandler->prefers('vcf'):
						;
						break;

				} // switch
				// $this->theme = null;
			}

			$this->set('commentModel', 'Comment');

			if (isset($this->{$this->modelClass}->_schema) && array_key_exists('views', $this->{$this->modelClass}->_schema) && (!isset($this->params['prefix']) || isset($this->params['prefix']) && $this->params['prefix'] != 'admin') && in_array($this->params['action'], $this->viewableActions)) {
					$this->{$this->modelClass}->Behaviors->attach('Libs.Viewable');
			}
		}

		/**
		 * add css from other controllers.
		 *
		 * way to inject css from plugins to the layout. call addCss(false) to
		 * clear current stack, call addCss(true) to get a list back of what is there.
		 *
		 * @param mixed $css array of paths like HtmlHelper::css or a string path
		 */
		function addCss($css = false){			
			return $this->__loadAsset($css, __FUNCTION__);
		}

		/**
		 * add js from other controllers.
		 *
		 * way to inject js from plugins to the layout. call addJs(false) to
		 * clear current stack, call addJs(true) to get a list back of what is there.
		 *
		 * @param mixed $js array of paths like HtmlHelper::css or a string path
		 */
		function addJs($js = false){
			return $this->__loadAsset($js, __FUNCTION__);
		}


		function __loadAsset($data, $method){
			$property = '__'.$method;
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

		function render($action = null, $layout = null, $file = null) {
			if(($this->action == 'admin_edit' || $this->action == 'admin_add')) {
				$viewPath = App::pluginPath($this->params['plugin']) . 'views' . DS . $this->viewPath . DS . $this->action . '.ctp';
				if(!file_exists($viewPath)) {
					$action = 'admin_form';
				}
			}
			else if(($this->action == 'edit' || $this->action == 'add')) {
				$viewPath = App::pluginPath($this->params['plugin']) . 'views' . DS . $this->viewPath . DS . $this->action . '.ctp';
				if(!file_exists($viewPath)) {
					$action = 'form';
				}
			}

			return parent::render($action, $layout, $file);
		}

		function blackHole(&$controller, $error){
			pr('you been blackHoled');
			pr($error);
			exit;
		}

		function afterFilter(){
			if(Configure::read('debug') === 0){
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
		}
	}

	/**
	 * seperating the global methods so the controller is a bit cleaner.
	 *
	 * basicaly all the methods like _something should be moved to a component
	 */
	class GlobalActions extends Controller{
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

				if ($this->{$this->modelClass}->createComment($id, $this->data)) {
					$this->Session->setFlash(__($message, true));
					$this->redirect($this->referer());
				}
				else {
					$this->Session->setFlash(__('Your comment was not saved. Please check for errors and try again', true));
				}
			}
		}

		/**
		* Common method for rating.
		*
		* This is the default method for a rating, if you would like to change
		* the way it works for your own plugin just define your own method in the
		* plugins app_controller or the actual controller.
		*
		* By default it will check if users need to be logged in before rating and
		* redirect if they must and are not. else it will get the ip address and then
		* save the rating.
		*
		* @param int $id the id of the itme you are rating.
		*
		* @return null, will redirect.
		*
		* @todo check if the model is a rateable model.
		*/
		function rate($id = null) {
			$this->data['Rating']['ip'] = $this->RequestHandler->getClientIP();
			$this->data['Rating']['user_id'] = $this->Session->read('Auth.User.id');
			$this->data['Rating']['class'] = isset($this->data['Rating']['class']) ? $this->data['Rating']['class']: ucfirst($this->params['plugin']).'.'.$this->modelClass;
			$this->data['Rating']['foreign_id'] = isset($this->data['Rating']['foreign_id']) ? $this->data['Rating']['foreign_id'] : $id;
			$this->data['Rating']['rating'] = isset($this->data['Rating']['rating']) ? $this->data['Rating']['rating'] : $this->params['named']['rating'];

			$this->log(serialize($this->data['Rating']));

			if (Configure::read('Rating.require_auth') === true && !$this->data['Rating']['user_id']) {
				$this->Session->setFlash(__('You need to be logged in to rate this item',true));
				$this->redirect('/login');
			}

			if (!empty($this->data['Rating']['rating'])) {
				if ($this->{$this->modelClass}->rateRecord($this->data)) {
					$data = $this->{$this->modelClass}->find(
						'first',
						array(
							'fields' => array(
								$this->modelClass.'.rating',
								$this->modelClass.'.rating_count'
							),
							'conditions' => array(
								$this->modelClass.'.id' => $this->data['Rating']['foreign_id']
							)
						)
					);
					$message = sprintf(__('Saved! new rating %s (out of %s)', true), $data[$this->modelClass]['rating'], $data[$this->modelClass]['rating_count']);
				}
				else {
					$message = __('It seems you have already voted for this item.', true);
				}

				if(!$this->RequestHandler->isAjax()){
					$this->Session->setFlash($message);
					$this->redirect($this->referer());
				}
				else{
					Configure::write('debug', 0);
					$this->set('json', array('message' => $message));
				}
			}
		}

		/**
		* Some global methods for admin
		*/
		/**
		* get a list of all the plugins in the app
		*/
		function admin_getPlugins(){
			$this->set('json', array('' => __('Please select', true)) + $this->{$this->modelClass}->getPlugins());
		}

		/**
		* get a list of all the controllers for the selected plugin
		*/
		function admin_getControllers(){
			if (!isset($this->params['named']['plugin'])) {
				$this->set('json', array('error'));
				return;
			}
			$this->set('json', array('' => __('Please select', true)) + $this->{$this->modelClass}->getControllers($this->params['named']['plugin']));
		}

		/**
		* get a list of all the actions for the selected plugin + controller
		*/
		function admin_getActions(){
			if (!(isset($this->params['named']['plugin']) && isset($this->params['named']['controller'] ))) {
				$this->set('json', array('error'));
				return;
			}
			$this->set('json', array('' => __('Please select', true)) + $this->{$this->modelClass}->getActions($this->params['named']['plugin'], $this->params['named']['controller']));
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
						$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->Infinitas->_getPluginControllerName($ctrlName)));
						$controllerNode = $aco->save();
						$controllerNode['Aco']['id'] = $aco->id;
						$log[] = 'Created Aco node for ' . $this->Infinitas->_getPluginControllerName($ctrlName) . ' ' . $this->Infinitas->_getPluginName($ctrlName) . ' Plugin Controller';
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
		function admin_delete() {
			try {
				throw new Exception('Please use the delete through the mass action handler.');
			} catch (Exception $e) {
				echo 'Depreciated: ',  $e->getMessage(), ' Where: ', __METHOD__, ' Line: ',  __LINE__, "\n";
			}
			exit;
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
			$model = $this->modelClass;

			if (!$id) {
				$this->Session->setFlash('That ' . $model . ' could not be found', true);
				$this->redirect($this->referer());
			}

			$this->data[$model]['id'] = $id;

			if (!isset($this->params['named']['position']) && isset($this->$model->actsAs['Libs.Sequence'])) {
				$this->Session->setFlash(__('A problem occured moving the ordered record.', true));
				$this->redirect($this->referer());
			}

			if (!isset($this->params['named']['direction']) && isset($this->$model->actsAs['Tree'])) {
				$this->Session->setFlash(__('A problem occured moving the MPTT record.', true));
				$this->redirect($this->referer());
			}

			if (isset($this->params['named']['position'])) {
				$this->Infinitas->_orderedMove();
			}

			if (isset($this->params['named']['direction'])) {
				$this->Infinitas->_treeMove();
			}

			$this->redirect($this->referer());
		}

		/**
		 * depreciated methods
		 */
		function admin_commentPurge($class = null) {
			echo 'moved to comments';
		}
	}
