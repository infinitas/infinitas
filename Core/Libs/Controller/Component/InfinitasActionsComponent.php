<?php
	/**
	 * Some global methods for admin
	 */
	class InfinitasActionsComponent extends InfinitasComponent {
		/**
		 * @brief get a list of all the plugins in the app
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function actionAdminGetPlugins(){
			$this->Controller->set('json', $this->Controller->{$this->Controller->modelClass}->getPlugins());
		}

		/**
		 * @brief get a list of all the controllers for the selected plugin
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function actionAdminGetControllers(){
			if (!isset($this->Controller->request->data[$this->Controller->modelClass]['plugin'])) {
				$this->Controller->set('json', array('error'));
				return;
			}

			$this->Controller->set(
				'json',
				$this->Controller->{$this->Controller->modelClass}->getControllers(
					$this->Controller->request->data[$this->Controller->modelClass]['plugin']
				)
			);
		}

		/**
		 * @brief get a list of all the models for the selected plugin
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function actionAdminGetModels(){
			if (!isset($this->Controller->request->data[$this->Controller->modelClass]['plugin'])) {
				$this->Controller->set('json', array('error'));
				return;
			}

			$this->Controller->set(
				'json',
				$this->Controller->{$this->Controller->modelClass}->getModels(
					$this->Controller->request->data[$this->Controller->modelClass]['plugin']
				)
			);
		}

		/**
		 * @brief get a list of all the actions for the selected plugin + controller
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function actionAdminGetActions(){
			if (!(isset($this->Controller->request->data[$this->Controller->modelClass]['plugin']) &&
					isset($this->Controller->request->data[$this->Controller->modelClass]['controller'] ))) {
				$this->Controller->set('json', array('error'));
				return;
			}
			
			$this->Controller->set(
				'json',
				$this->Controller->{$this->Controller->modelClass}->getActions(
					$this->Controller->request->data[$this->Controller->modelClass]['plugin'],
					$this->Controller->request->data[$this->Controller->modelClass]['controller']
				)
			);
		}

		/**
		 * @brief get a list of all the actions for the selected plugin + controller
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function actionAdminGetRecords(){
			if (!(isset($this->Controller->request->data[$this->Controller->modelClass]['plugin']) &&
					isset($this->Controller->request->data[$this->Controller->modelClass]['model'] ))) {
				$this->Controller->set('json', array('error'));
				return;
			}
			
			$this->Controller->set(
				'json',
				$this->Controller->{$this->Controller->modelClass}->getRecords(
					$this->Controller->request->data[$this->Controller->modelClass]['plugin'],
					$this->Controller->request->data[$this->Controller->modelClass]['model']
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
		public function actionAdminAdd() {
			if (!empty($this->Controller->request->data)) {
				$this->Controller->{$this->Controller->modelClass}->create();
				
				if ($this->Controller->{$this->Controller->modelClass}->saveAll($this->Controller->request->data)) {
					$this->Controller->notice('saved');
				}
				
				$this->Controller->notice('not_saved');
			}

			$this->Controller->saveRedirectMarker();
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
		public function actionAdminEdit($id = null, $query = array()) {
			if(empty($this->Controller->request->data) && !$id){
				$this->Controller->notice('invalid');
			}

			if (!empty($this->Controller->request->data)) {
				if ($this->Controller->{$this->Controller->modelClass}->saveAll($this->Controller->request->data)) {
					$this->Controller->notice('saved');
				}
				
				$this->Controller->notice('not_saved');
			}

			if(empty($this->Controller->request->data) && $id){
				$query['conditions'][$this->Controller->{$this->Controller->modelClass}->alias . '.' . $this->Controller->{$this->Controller->modelClass}->primaryKey] = $id;

				$this->Controller->request->data = $this->Controller->{$this->Controller->modelClass}->find('first', $query);
				if(empty($this->Controller->request->data)){
					$this->Controller->notice('invalid');
				}
			}

			$this->Controller->saveRedirectMarker();
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
		public function actionAdminPreview($id = null) {
			if(!is_callable(array($this->Controller->{$this->Controller->modelClass}, 'getViewData'))){
				$this->Controller->notice(
					__('There is no preview available'),
					array(
						'level' => 'error',
						'redirect' => Router::url('/')
					)
				);
			}

			$varName = Inflector::variable($this->Controller->modelClass);

			$$varName = $this->Controller->{$this->Controller->modelClass}->getViewData(
				array(
					$this->Controller->modelClass . '.' . $this->Controller->{$this->Controller->modelClass}->primaryKey => $id
				)
			);

			$this->Controller->set($varName, $$varName);
			$this->Controller->request->params['admin'] = false;
			$this->Controller->layout = 'front';
			
			$this->Controller->render('view');
		}
		
		public function actionAdminExport($id = null) {
			$this->Controller->notice(
				__d($this->Controller->request->params['plugin'], 'Export is currently disabled'),
				array(
					'redirect' => true,
					'level' => 'warning'
				)
			);
		}

		/**
		 * @brief prg method to show the users documents
		 *
		 * redirects to the filtered url for the users own records
		 */
		public function actionAdminMine(){
			if(!$this->Controller->{$this->Controller->modelClass}->hasField('user_id')) {
				$this->Controller->notice(
					__('Cant determin a user field'),
					array(
						'redirect' => true,
						'level' => 'error'
					)
				);
			}

			if(!$this->Controller->Auth->user('id')) {
				$this->Controller->notice(
					__('You need to be logged in to do that'),
					array(
						'redirect' => true,
						'level' => 'error'
					)
				);
			}

			$this->Controller->redirect(
				array(
					'action' => 'index',
					$this->Controller->{$this->Controller->modelClass}->alias . '.user_id' => $this->Controller->Auth->user('id')
				)
			);
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
		public function actionAdminReorder($id = null) {
			$model = $this->Controller->modelClass;

			if (!$id) {
				$this->Controller->notice('invalid');
			}

			$redirectConfig = array(
				'level' => 'error',
				'redirect' => true
			);
			$this->Controller->request->data[$model]['id'] = $id;

			if (!empty($this->Controller->request->params['named']['position'])) {
				if(!$this->Controller->{$model}->Behaviors->attached('Sequence')) {
					$this->Controller->notice(
						__('A problem occured moving the ordered record.'),
						$redirectConfig
					);
				}
				
				$this->Controller->Infinitas->orderedMove();
			}

			if (!empty($this->Controller->request->params['named']['direction'])) {
				if(!$this->Controller->{$model}->Behaviors->attached('Tree')) {
					$this->Controller->notice(
						__('A problem occured moving that MPTT record.'),
						$redirectConfig
					);
				}
				
				$this->Controller->Infinitas->treeMove($this->Controller->request->params['named']['direction']);
			}

			$this->Controller->redirect($this->Controller->referer());
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
		public function actionRate($id = null) {
			$this->Controller->request->data['Rating']['ip'] = $this->Controller->RequestHandler->getClientIP();
			$this->Controller->request->data['Rating']['user_id'] = $this->Controller->Auth->user('id');
			$tempClass = $this->Controller->request->plugin . '.' . $this->Controller->modelClass;
			$this->Controller->request->data['Rating']['class'] = isset($this->Controller->request->data['Rating']['class']) ? $this->Controller->request->data['Rating']['class'] : $tempClass;
			$this->Controller->request->data['Rating']['foreign_id'] = isset($this->Controller->request->data['Rating']['foreign_id']) ? $this->Controller->request->data['Rating']['foreign_id'] : $id;
			$this->Controller->request->data['Rating']['rating'] = isset($this->Controller->request->data['Rating']['rating']) ? $this->Controller->request->data['Rating']['rating'] : $this->Controller->request->params['named']['rating'];

			$this->Controller->log(serialize($this->Controller->request->data['Rating']));

			if (Configure::read('Rating.require_auth') === true && !$this->Controller->request->data['Rating']['user_id']) {
				$this->Controller->notice(
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

			if (!empty($this->Controller->request->data['Rating']['rating'])) {
				$message = __('It seems you have already voted for this item.');

				if ($this->Controller->{$this->Controller->modelClass}->rateRecord($this->Controller->request->data)) {
					$data = $this->Controller->{$this->Controller->modelClass}->find(
						'first',
						array(
							'fields' => array(
								$this->Controller->modelClass.'.rating',
								$this->Controller->modelClass.'.rating_count'
							),
							'conditions' => array(
								$this->Controller->modelClass.'.id' => $this->Controller->request->data['Rating']['foreign_id']
							)
						)
					);
					$message = sprintf(__('Saved! new rating %s (out of %s)'), $data[$this->Controller->modelClass]['rating'], $data[$this->Controller->modelClass]['rating_count']);
				}

				if($this->Controller->RequestHandler->isAjax()){
					Configure::write('debug', 0);
					$this->Controller->set('json', array('message' => $message));
				}

				$this->Controller->notice(
					$message,
					array(
						'redirect' => true
					)
				);
			}
		}
	}