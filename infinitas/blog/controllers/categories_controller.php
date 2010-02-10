<?php
	/**
	 * Blog categories controller
	 *
	 * Controller for crud of blog categories
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package blog
	 * @subpackage blog.controllers.categories
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CategoriesController extends BlogAppController {
		/**
		* Class name.
		*
		* @access public
		* @var string
		*/
		var $name = 'Categories';

		/**
		* Helpers.
		*
		* @access public
		* @var array
		*/
		var $helpers = array(
			'Filter.Filter'
		);

		/**
		* PostsController::beforeFilter()
		*
		* empty
		*/
		function beforeFilter() {
			parent::beforeFilter();
		}

		/**
		* Index for users
		*
		* @param string $tag used to find posts with a tag
		* @param string $year used to find posts in a cetain year
		* @param string $month used to find posts in a year and month needs year
		* @return
		*/
		function index() {
			$post_ids = '';
			if (isset($tag) && strtolower($tag) != 'all') {
				$post_ids = $this->Post->Tag->findPostsByTag($tag);
			}

			$this->paginate = array(
				'fields' => array(
					'Post.id',
					'Post.title',
					'Post.slug',
					'Post.body',
					'Post.intro',
					'Post.comment_count',
					'Post.created',
					'Post.parent_id',
					'Post.ordering',
					'Post.category_id',
				),
				'conditions' => array(
					'Post.active' => 1,
					'Post.id' . ((!empty($post_ids)) ? ' IN (' . implode(',', $post_ids) . ')' : ' > 0'),
					'Post.parent_id' => null
				),
				'contain' => array(
					'Tag' => array(
						'fields' => array(
							'Tag.name'
						)
					),
					'Category' => array(
						'fields' => array(
							'Category.id',
							'Category.name',
							'Category.slug',
						)
					)
				)
			);

			$posts = $this->paginate('Post');
			$this->set(compact('posts'));

			if( $this->RequestHandler->isRss() ){
				//$this->render('index');
			}
		}

		/**
		* Admin Section.
		*
		* All the admin methods.
		*/
		/**
		* Admin index.
		*
		* Uses the {@see FilterComponent} component to filter results.
		*
		* @return na
		*/
		function admin_index() {
			$this->Category->recursive = 1;
			$categories = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);

			$this->set(compact('categories', 'filterOptions'));
		}

		/**
		* Admin add.
		*
		* This does some trickery for creating tags from the textarea comma
		* delimited. also makes sure there are no duplicates created.
		*
		* @return na
		*/
		function admin_add() {
			if (!empty($this->data)) {
				$this->Category->create();
				if ($this->Category->save($this->data)) {
					$this->Session->setFlash('Your category has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That category could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Category->save($this->data)) {
					$this->Session->setFlash(__('Your category has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your category could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Category->read(null, $id);
			}
		}
	}
?>