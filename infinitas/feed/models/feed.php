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

	class Feed extends FeedAppModel {
		var $name = 'Feed';
		var $actsAs = array(
			// 'Libs.Feedable',
			// 'Libs.Commentable',
			// 'Libs.Rateable
		);

		var $order = array(
		);

		var $hasOne = array(
		);

		var $belongsTo = array(
			'Group' => array(
				'className' => 'Management.Group',
				'fields' => array(
					'Group.id',
					'Group.name'
				)
			),
		);

		var $hasMany = array(
		);

		var $hasAndBelongsToMany = array(
			'FeedItem' => array(
				'className'              => 'Management.FeedItem',
				'joinTable'              => 'core_feeds_feed_items',
				'with'                   => 'Management.FeedsFeedItem',
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

		function __construct($id = false, $table = null, $ds = null) {
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
				'order' => array(
					'validateJson' => array(
						'rule' => 'validateJson',
						'message' => __('Your order is not valid', true)
					)
				),
			);
		}

		function getFeed($id = null, $group_id = 999){
			if(!$id){
				return array();
			}

			$feed = $this->find(
				'first',
				array(
					'conditions' => array(
						'Feed.active' => 1,
						'Feed.group_id > ' => $group_id,
						'Feed.id' => $id
					),
					'contain' => array(
						'FeedItem'
					)
				)
			);

			$data = $this->feedArrayFormat($this->getJsonRecursive($feed));

			exit;
		}

		function feedArrayFormat($feed = array()){
			if (empty($feed)){
				return array();
			}

			$query['fields']     = $feed['Feed']['fields'];
			//$query['conditions'] = $feed['Feed']['conditions'];
			$query['order']      = $feed['Feed']['order'];
			$query['limit']      = $feed['Feed']['limit'];

			foreach($feed['FeedItem'] as $item){
				$query['feed'][] = array(
					'fields'     => $item['fields'],
					//'conditions' => $item['conditions'],
					'limit'      => $item['limit']
				);
			}

			$_Model = ClassRegistry::init($feed['Feed']['plugin'].'.'.Inflector::singularize($feed['Feed']['controller']));

			pr($_Model->find('all', $query));
			exit;
		}
	}