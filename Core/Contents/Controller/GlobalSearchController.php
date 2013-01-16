<?php
/**
 * GlobalSearchController
 *
 * @package Infinitas.Contents.Controller
 */

/**
 * GlobalSearchController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contents.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GlobalSearchController extends ContentsAppController {
/**
 * Custom loaded model
 *
 * @var array
 */
	public $uses = array(
		'Contents.GlobalContent'
	);

/**
 * Search for content
 *
 * @param string $term the search term
 *
 * @return void
 */
	public function search($term = null) {
		if (!empty($this->data[$this->modelClass]['search'])) {
			$url = array(
				'action' => 'search',
				Sanitize::paranoid($this->data[$this->modelClass]['search'], array(
					'-', ' '
				)),
				'global_category_id' => !empty($this->data[$this->modelClass]['global_category_id']) ? $this->data[$this->modelClass]['global_category_id'] : null
			);

			$this->redirect($url);
		}

		$category = !empty($this->request->params['named']['global_category_id']) ? $this->request->params['named']['global_category_id'] : null;
		try {
			$this->Paginator->settings = array('search', Sanitize::paranoid($term), $category);
			$this->set('search', $this->Paginator->paginate());
		} catch(Exception $e) {
			$this->notice($e);
		}

		$this->request->data[$this->modelClass]['global_category_id'] = $category;

		$this->set('globalCategories', array_merge(
			array(null => __d('contents', 'All')),
			$this->{$this->modelClass}->find('categoryList')
		));
	}

}