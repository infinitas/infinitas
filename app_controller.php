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
* @link http://www.dogmatic.co.za
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class AppController extends Controller {

	var $view = 'Theme';

	var $helpers = array(
		'Html', 'Form', 'Javascript',

		'Libs.Status', 'Libs.Image', 'Libs.Design'
		);

	var $components = array(
		'Libs.Infinitas', 'Core.CoreConfig',
		// cake components
		'Session','RequestHandler',
		// core components
		'DebugKit.Toolbar', // 'Libs.Cron',
		// components
		'Filter.Filter' => array(
			'actions' => array('admin_index')
			)
		);

	/**
	* actions where viewable will work.
	*/
	var $viewableActions = array(
		'view'
		);

	function beforeFilter() {
		parent::beforeFilter();

		if (isset($this->data['PaginationOptions']['pagination_limit'])) {
			$this->Infinitas->changePaginationLimit( $this->data['PaginationOptions'], $this->params );
		}

		if (isset($this->params['named']['limit'])) {
			$this->params['named']['limit'] = $this->Infinitas->paginationHardLimit($this->params['named']['limit']);
		}

		if (Configure::read('Website.force_www')) {
			$this->Infinitas->forceWwwUrl();
		}

		$this->Session->write('Auth', ClassRegistry::init('Core.User')->find('first', array('conditions' => array('User.id' => 1))));

		if (sizeof($this->uses) && (isset($this->{$this->modelClass}->Behaviors) && $this->{$this->modelClass}->Behaviors->attached('Logable'))) {
			$this->{$this->modelClass}->setUserData($this->Session->read('Auth'));
		}

		//$this->layout = $this->Infinitas->getCorrectLayout($this->params);

		$this->set('commentModel', 'Comment');

		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin' && !in_array($this->params['action'], $this->viewableActions)) {
			if (isset($this->{$this->modelClass}->Behaviors)) {
				$this->{$this->modelClass}->Behaviors->detach('Viewable');
			}
		}
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
	* - up:       moves the record up.
	* - down:     moves the record down.
	* - position: sets the position for the record.
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

		if (!isset($this->params['named']['direction'])) {
			$this->Session->setFlash(__('Please select the direction you would like to move the record.', true));
			$this->redirect($this->referer());
		}

		$amount = (isset($this->params['named']['amount'])) ? $this->params['named']['amount'] : 1;

		switch ($this->params['named']['direction']) {
			case 'position':
				/**
				*
				* @todo set the position of the record after add
				*/
				break;

			case 'up':
				$this->$model->moveup($id, $amount);
				break;

			case 'down':
				$this->$model->movedown($id, $amount);
				break;
		} // switch
		$this->redirect($this->referer());
	}

	/**
	* toggle records with an active table that is tinyint(1).
	*
	* @todo -c"AppController" Implement AppController.
	* - check the table has "active" field
	* - check its tinyint(1)
	* - make better with saveField and not reading the whole record.
	* @param mixed $id the id of the record
	* @return n /a, redirects with different messages in {@see Session::setFlash}
	*/
	function admin_toggle($id = null) {
		$model = $this->modelNames[0];

		if (!$id) {
			$this->Session->setFlash('That ' . $model . ' could not be found', true);
			$this->redirect($this->referer());
		}

		$this->$model->id = $id;
		$this->$model->recursive = - 1;
		$__data = $this->$model->read();
		$__data[$model]['active'] = ($__data[$model]['active']) ? 0 : 1;

		if ($this->$model->save($__data, array('validate' => false))) {
			$this->Session->setFlash(sprintf(__('The ' . $model . ' is now %s', true), (($__data[$model]['active']) ? __('active', true) : __('disabled', true))));
			$this->redirect($this->referer());
		}

		$this->Session->setFlash('That ' . $model . ' could not be toggled', true);
		$this->redirect($this->referer());
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
		$model = $this->modelNames[0];

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

	function admin_mass() {
		$ids = $this->__massGetIds($this->data[$this->modelClass]);

		switch ($this->__massGetAction($this->params['form'])) {
			case 'delete':
				$this->__massActionDelete($ids);
				break;

			case 'toggle':
				$this->__massActionToggle($ids);
				break;

			case 'copy':
				$this->__massActionCopy($ids);
				break;

			case 'filter':
				$data = array();
				foreach( $this->data[$this->modelClass] as $k => $field ){
					if ( is_int( $k ) || $k == 'all' ){
						continue;
					}
					$data[$this->modelClass.'.'.$k] = $field;
				}
				$this->redirect(array(
						'plugin' => $this->params['plugin'],
						'controller' => $this->params['controller'],
						'action' => 'index'
					) + $this->params['named'] + $data
				);
				break;

			default:
				$this->__massActionGeneric($this->__massGetAction($this->params['form']), $ids);
				break;
		} // switch
	}

	function __massGetIds($data) {
		if (in_array($this->__massGetAction($this->params['form']), array('add','filter'))) {
			return null;
		}

		$ids = array();
		foreach($data as $id => $selected) {
			if (!is_int($id)) {
				continue;
			}

			if ($selected) {
				$ids[] = $id;
			}
		}

		if (empty($ids)) {
			$this->Session->setFlash(__('Nothing was selected, please select something and try again.', true));
			$this->redirect($this->referer());
		}

		return $ids;
	}

	function __massGetAction($form) {
		if (isset($form['action'])) {
			return $form['action'];
		}

		$this->Session->setFlash(__('I dont know what to do.', true));
		$this->redirect($this->referer());
	}

	function __massActionDelete($ids) {
		$model = $this->modelNames[0];

		$conditions = array($model . '.' . $this->$model->primaryKey => $ids
			);

		if ($this->$model->deleteAll($conditions)) {
			$this->Session->setFlash(__('The ' . $model . '\'s have been deleted', true));
			$this->redirect($this->referer());
		}

		$this->Session->setFlash(__('The ' . $model . '\'s could not be deleted', true));
		$this->redirect($this->referer());
	}

	function __massActionToggle($ids) {
		$model = $this->modelNames[0];
		$this->$model->recursive = - 1;
		$ids = $ids + array(0);

		if ($this->$model->updateAll(
				array($model . '.active' => '1 - `' . $model . '`.`active`'),
					array($model . '.id IN(' . implode(',', $ids) . ')')
					)
				) {
			$this->Session->setFlash(__('The ' . $model . '\'s were toggled', true));
			$this->redirect($this->referer());
		}

		$this->Session->setFlash(__('The ' . $model . '\'s could not be toggled', true));
		$this->redirect($this->referer());
	}

	function __massActionCopy($ids) {
		$model = $this->modelNames[0];
		$this->$model->recursive = - 1;

		$copyText = sprintf('- %s ( %s )', __('copy', true), date('Y-m-d'));

		$saves = 0;
		foreach($ids as $id) {
			$record = $this->$model->read(null, $id);
			unset($record[$model]['id']);

			if ($record[$model][$this->$model->displayField] != $this->$model->primaryKey) {
				$record[$model][$this->$model->displayField] = $record[$model][$this->$model->displayField] . $copyText;
			}

			$this->$model->create();

			if ($this->$model->save($record)) {
				$saves++;
			} ;
		}

		if ($saves) {
			$this->Session->setFlash(__($saves . ' copies of ' . $model . ' was made', true));
			$this->redirect($this->referer());
		}

		$this->Session->setFlash(__('No copies could be made.', true));
		$this->redirect($this->referer());
	}

	function __massActionGeneric($action, $ids) {
		if (!$ids) {
			$this->redirect(array('action' => $action));
		}

		foreach($ids as $id) {
			$this->redirect(array('action' => $action, $id));
		}
	}
}

?>