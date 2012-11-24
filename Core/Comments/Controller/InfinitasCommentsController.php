<?php
/**
 * InfinitasCommentsController
 *
 * @package Infinitas.Comments.Controller
 */

/**
 * InfinitasCommentsController
 *
 * CommentsController is used for the management of comments allowing admins to
 * view, toggle and delete as needed.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Comments.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasCommentsController extends CommentsAppController {

/**
 * View a list of comments
 *
 * @return void
 */
	public function index() {
		$conditions = array(
			'Comment.active' => 1
		);

		if (isset($this->request->params['named']['Comment.class'])) {
			$conditions['Comment.class'] = $this->request->params['named']['Comment.class'];
		}

		if (isset($this->request->params['named']['Comment.foreign_id'])) {
			$conditions['Comment.foreign_id'] = $this->request->params['named']['Comment.foreign_id'];
		}

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array(
				'CommentAttribute'
			)
		);

		$this->set('comments', $this->Paginator->paginate());
	}

/**
 * Mass action
 *
 * If not doing spam will pass to usual mass action libs
 *
 * @return void
 */
	public function admin_mass() {
		$action = $this->MassAction->getAction();
		if ($action == 'spam') {
			$this->__removeSpam();
		}

		parent::admin_mass();
	}

/**
 * List all comments
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array('adminIndex');
		$comments = $this->Paginator->paginate(null, $this->Filter->filter);

		foreach ($comments as &$comment) {
			$class = ClassRegistry::init(ucfirst($comment[$this->modelClass]['class']));
			if (isset($class->contentable) && $class->contentable) {
				$class = ClassRegistry::init('Contents.GlobalContent');
				$list = $class->find('list', array(
					'fields' => array(
						$class->alias . '.id',
						$class->alias . '.title'
					),
					'conditions' => array(
						$class->alias . '.foreign_key' => $comment[$this->modelClass]['foreign_id']
					)
				));
				if (empty($list)) {
					$list = array(__d('comments', 'Invalid Record'));
				}
				$comment[$this->modelClass]['post'] = current($list);
			} else {
				$class->id = $comment[$this->modelClass]['foreign_id'];
				$comment[$this->modelClass]['post'] = $class->field($class->displayField);
			}
		}

		$filterOptions = $this->Filter->filterOptions;

		$filterOptions['fields'] = array(
			'class' => $this->InfinitasComment->getUniqueClassList(),
			'email',
			'comment',
			'active' => Configure::read('CORE.active_options')
		);

		$this->set(compact('comments', 'filterOptions'));
	}

/**
 * Reply to comments
 *
 * @return void
 */
	public function admin_reply() {
	}

/**
 * Ban an IP that is generating spam
 *
 * @param string $ip
 */
	public function admin_ban($ip) {
		try {
			$this->InfinitasComment->blockIp($ip);
			$this->notice(__d('comments', 'Commenters have been banned'), array(
				'redirect' => true
			));
		} catch(Exception $e) {
			$this->notice($e);
		}
	}

/**
 * Purge old inactive comments
 *
 * @param string $class the class comments should be purged from
 *
 * @return void
 */
	public function admin_commentPurge($class = null) {
		if (!$class) {
			$this->notice(
				__('Nothing chosen to purge'),
				array(
					'redirect' => true
				)
			);
		}

		if (!Configure::read('Comments.purge')) {
			$this->notice(
				__('Purge is disabled'),
				array(
					'redirect' => true
				)
			);
		}

		$ids = ClassRegistry::init('Comment.Comment')->find('list', array(
			'fields' => array(
				'Comment.id',
				'Comment.id'
			),
			'conditions' => array(
				'Comment.class' => $class,
				'Comment.active' => 0,
				'Comment.created < ' => date('Y-m-d H:i:s', strtotime('-' . Configure::read('Comments.purge')))
			)
		));

		if (empty($ids)) {
			$this->notice(
				__('Nothing to purge'),
				array(
					'redirect' => true
				)
			);
		}

		$counter = 0;

		foreach ($ids as $id) {
			if (ClassRegistry::init('Comment.Comment')->delete($id)) {
				$counter++;
			}
		}

		$this->notice(
			sprintf(__('%s comments were purged.'), $counter),
			array(
				'redirect' => true
			)
		);
	}

/**
 * Mass action remove spam handler
 *
 * @return void
 */
	private function __removeSpam() {
		if ($this->{$this->modelClass}->deleteAll(array($this->modelClass . '.status' => 'spam'))) {
			$this->notice(
				__d('comments', 'Spam comments have been removed'),
				array(
					'redirect' => true
				)
			);
		}

		$this->notice(
			__d('comments', 'Spam comments could not be removed'),
			array(
				'redirect' => true,
				'level' => 'warning'
			)
		);
	}
}