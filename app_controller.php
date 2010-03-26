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
		/**
		*
		*/
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

		/**
		* normal before filter.
		*/
		function beforeFilter() {
			parent::beforeFilter();

			$this->Infinitas->_setupAuth();
			$this->Infinitas->_setupSecurity();
			$this->Infinitas->_setupJavascript();

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

			// @todo check this still works
			if (isset($this->params['prefix']) && $this->params['prefix'] != 'admin' && in_array($this->params['action'], $this->viewableActions)) {
				if (!isset($this->{$this->modelClass}->Behaviors) && array_key_exists('views', $this->{$this->modelClass}->_schema)) {
					$this->{$this->modelClass}->Behaviors->attach('Viewable');
				}
			}
		}

		function beforeRender(){
			parent::beforeRender();
		}

		function blackHole(&$controller, $error){
			pr('you been blackHoled');
			pr($error);
			exit;
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

				if ($this->Post->createComment($id, $this->data)) {
					$this->Session->setFlash(__($message, true));
					$this->redirect($this->referer());
				} else {
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

		/**
		* Some global methods for admin
		*/
		/**
		* get a list of all the plugins in the app
		*/
		function admin_getPlugins(){
			$this->set('json', $this->{$this->modelClass}->getPlugins());
		}

		/**
		* get a list of all the controllers for the selected plugin
		*/
		function admin_getControllers(){
			if (!isset($this->params['named']['plugin'])) {
				$this->set('json', array('error'));
				return;
			}
			$this->set('json', $this->{$this->modelClass}->getControllers($this->params['named']['plugin']));
		}

		/**
		* get a list of all the actions for the selected plugin + controller
		*/
		function admin_getActions(){
			if (!(isset($this->params['named']['plugin']) && isset($this->params['named']['controller'] ))) {
				$this->set('json', array('error'));
				return;
			}
			$this->set('json', $this->{$this->modelClass}->getActions($this->params['named']['plugin'], $this->params['named']['controller']));
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

			if (!isset($this->params['named']['possition']) && isset($this->$model->actsAs['Libs.Sequence'])) {
				$this->Session->setFlash(__('A problem occured moving the ordered record.', true));
				$this->redirect($this->referer());
			}

			if (!isset($this->params['named']['possition']) && isset($this->$model->actsAs['Tree'])) {
				$this->Session->setFlash(__('A problem occured moving the MPTT record.', true));
				$this->redirect($this->referer());
			}

			if (isset($this->params['named']['possition'])) {
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
?>