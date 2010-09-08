<?php
	/**
	 * Frontpage model
	 *
	 * This is items that will be show on the frontpage of the cms. It just stores
	 * the fk of the content itme and the order it should be displayed in.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package infinitas.cms
	 * @subpackage infinitas.cms.models.frontpage
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Frontpage extends CmsAppModel {
		/**
		 * Model name
		 * 
		 * @access public
		 * @var string
		 */
		public $name = 'Frontpage';

		/**
		 * direct delete or to trash
		 *
		 * @access public
		 * @var bool
		 */
		public $noTrash = true;

		/**
		 * skip confirmation
		 *
		 * @access public
		 * @var bool
		 */
		public $noConfirm = true;

		/**
		 * Default ordering for index pages
		 *
		 * @access public
		 * @var array
		 */
		public $order = array(
			'Frontpage.order_id' => 'ASC',
			'Frontpage.ordering' => 'ASC'
		);

		/**
		 * belongsTo relations
		 *
		 * @access public
		 * @var array
		 */
		public $belongsTo = array(
			'Content' => array(
				'className' => 'Cms.Content',
				'fields' => array(
					'Content.id',
					'Content.title',
					'Content.slug',
					'Content.body',
					'Content.active',
					'Content.comment_count',
					'Content.created',
					'Content.modified'
				)
			)
		);
	}