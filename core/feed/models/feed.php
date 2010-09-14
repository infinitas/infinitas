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
		public $name = 'Feed';
		public $actsAs = array(
			// 'Libs.Commentable',
			// 'Libs.Rateable
		);

		public $order = array(
		);

		public $hasOne = array(
		);

		public $belongsTo = array(
			'Group' => array(
				'className' => 'Management.Group',
				'fields' => array(
					'Group.id',
					'Group.name'
				)
			),
		);

		public $hasMany = array(
		);

		public $hasAndBelongsToMany = array(
			'FeedItem' => array(
				'className'              => 'Feed.FeedItem',
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
				'order' => array(
					'validateJson' => array(
						'rule' => 'validateJson',
						'message' => __('Your order is not valid', true)
					)
				),
			);
		}

		public function getFeed($id = null, $group_id = 999){
			if(!$id){
				return array();
			}

			$feed = $this->find(
				'first',
				array(
					'conditions' => array(
						'Feed.active' => 1,
						//'Feed.group_id > ' => $group_id,
						'Feed.id' => $id
					),
					'contain' => array(
						'FeedItem'
					)
				)
			);
			if(empty($feed)){
				return $feed;
			}

			return $this->feedArrayFormat($this->getJsonRecursive($feed));
		}

		public function feedArrayFormat($feed = array()){
			if (empty($feed)){
				return array();
			}

			$query['fields']     = $feed['Feed']['fields'];
			//$query['conditions'] = $feed['Feed']['conditions'];
			//$query['order']      = $feed['Feed']['order'];
			$query['limit']      = $feed['Feed']['limit'];
			$query['setup']      = array(
				'plugin' => $feed['Feed']['plugin'],
				'controller' => $feed['Feed']['controller'],
				'action' => $feed['Feed']['action']
			);

			foreach($feed['FeedItem'] as $item){
				$query['feed'][ucfirst($item['plugin']).'.'.ucfirst(Inflector::singularize($item['controller']))] = array(
					'setup' => array(
						'plugin' => $item['plugin'],
						'controller' => $item['controller'],
						'action' => $item['action']
					),
					'fields'     => $item['fields'],
					//'conditions' => $item['conditions'],
					//'limit'      => $item['limit']
				);
			}

			$_Model = ClassRegistry::init($feed['Feed']['plugin'].'.'.Inflector::singularize($feed['Feed']['controller']));

			return $_Model->find('feed', $query);
		}
	}