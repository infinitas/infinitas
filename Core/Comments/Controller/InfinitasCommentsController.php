<?php
	/**
	 * @brief CommentsController is used for the management of comments
	 *
	 * allowing admins to view, toggle and delete as needed.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Comments.controllers
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	class InfinitasCommentsController extends CommentsAppController {
		public function index(){
			$conditions = array(
				'Comment.active' => 1
			);

			if(isset($this->request->params['named']['Comment.class'])){
				$conditions['Comment.class'] = $this->request->params['named']['Comment.class'];
			}

			if(isset($this->request->params['named']['Comment.foreign_id'])){
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

		public function admin_index() {
			$this->Paginator->settings = array('adminIndex');
			$comments = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;

			$filterOptions['fields'] = array(
				'class' => $this->InfinitasComment->getUniqueClassList(),
				'email',
				'comment',
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('comments', 'filterOptions'));
		}

		public function admin_reply(){
			// @todo reply to the comment.
		}

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

			$ids = ClassRegistry::init('Comment.Comment')->find(
				'list',
				array(
					'fields' => array(
						'Comment.id',
						'Comment.id'
					),
					'conditions' => array(
						'Comment.class' => $class,
						'Comment.active' => 0,
						'Comment.created < ' => date('Y-m-d H:i:s', strtotime('-' . Configure::read('Comments.purge')))
					)
				)
			);

			if (empty($ids)) {
				$this->notice(
					__('Nothing to purge'),
					array(
						'redirect' => true
					)
				);
			}

			$counter = 0;

			foreach($ids as $id) {
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
	}