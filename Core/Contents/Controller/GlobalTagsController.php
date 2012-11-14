<?php
/**
 * GlobalTagsController
 *
 * @package Infinitas.Contents.Controller
 */

/**
 * GlobalTagsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contents.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GlobalTagsController extends ContentsAppController {
/**
 * List all tags
 *
 * @return void
 */
	public function admin_index() {
		$tags = $this->Paginator->paginate();

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'identifier',
			'keyname'
		);

		$this->set(compact('tags', 'filterOptions'));
	}

}
