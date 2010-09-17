<?php
	/**
	 * Feed model
	 *
	 * Add some documentation for Feed model.
	 *
	 * Copyright (c) {yourName}
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright     Copyright (c) 2009 {yourName}
	 * @link          http://infinitas-cms.org
	 * @package       Management
	 * @subpackage    Management.models.Feed
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	class FeedItem extends FeedAppModel {
		public $name = 'FeedItem';
		public $actsAs = array(
			// 'Libs.Feedable',
			// 'Libs.Commentable',
			// 'Libs.Rateable
		);

		public $order = array(
		);

		public $hasOne = array(
		);

		public $belongsTo = array(
			'Group' => array(
				'className' => 'Users.Group',
				'fields' => array(
					'Group.id',
					'Group.name'
				)
			),
		);

		public $hasMany = array(
		);

		public $hasAndBelongsToMany = array(
			'Feed' => array(
				'className'              => 'Feed.Feed',
				'joinTable'              => 'core_feeds_feed_items',
				'with'                   => 'Feed.FeedsFeedItem',
				'foreignKey'             => 'feed_id',
				'associationForeignKey'  => 'feed_item_id',
				'unique'                 => true,
				'conditions'             => '',
				'fields'                 => '',
				'order'                  => '',
				'limit'                  => '',
				'offset'                 => '',
				'finderQuery'            => '',
				'deleteQuery'            => '',
				'insertQuery'            => ''
			)
		);

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for your feed item', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a feed item with that name', true)
					)
				),
				'fields' => array(
					'validateJson' => array(
						'rule' => 'validateJson',
						'message' => __('Your fields are not valid', true)
					)
				),
				'conditions' => array(
					'validateJson' => array(
						'rule' => 'validateJson',
						'message' => __('Your conditions are not valid', true)
					)
				),
			);
		}
	}