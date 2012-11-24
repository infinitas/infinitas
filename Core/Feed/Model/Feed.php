<?php
/**
 * Feed
 *
 * @package Infinitas.Feed.Model
 */

/**
 * Feed
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Feed.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Feed extends FeedAppModel {
/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Users.Group',
			'fields' => array(
				'Group.id',
				'Group.name'
			)
		)
	);

/**
 * HasAndBelongsToMany relations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'FeedsFeed' => array(
			'className'			  => 'Feed.FeedsFeed',
			'joinTable'			  => 'global_feeds_feeds',
			'with'				   => 'Feed.FeedsFeed',
			'foreignKey'			 => 'main_feed_id',
			'associationForeignKey'  => 'sub_feed_id',
			'unique'				 => true,
			'conditions'			 => '',
			'fields'				 => '',
			'order'				  => '',
			'limit'				  => '',
			'offset'				 => '',
			'finderQuery'			=> '',
			'deleteQuery'			=> '',
			'insertQuery'			=> ''
		)
	);

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('feed', 'Please enter a name for your feed item')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('feed', 'There is already a feed item with that name')
				)
			),
			'fields' => array(
				'validateJson' => array(
					'rule' => 'validateJson',
					'message' => __d('feed', 'Your fields are not valid')
				)
			),
			'conditions' => array(
				'validateJson' => array(
					'rule' => 'validateJson',
					'message' => __d('feed', 'Your conditions are not valid')
				)
			),
			'order' => array(
				'validateJson' => array(
					'rule' => 'validateJson',
					'message' => __d('feed', 'Your order is not valid')
				)
			),
		);
	}

/**
 * List the available feeds
 *
 * @return array
 */
	public function listFeeds() {
		$feeds = $this->find(
			'all',
			array(
				'conditions' => array(
					$this->alias . '.active' => 1
				),
				'fields' => array(
					$this->alias . '.id',
					$this->alias . '.name',
					$this->alias . '.slug'
				)
			)
		);

		$return = array();
		foreach ($feeds as $feed) {
			$return[] = array(
				'name' => $feed[$this->alias]['name'],
				'url' => Router::url(array(
					'plugin' => 'feed',
					'controller' => 'feeds',
					'action' => 'view',
					'slug' => $feed['Feed']['slug']
				), true)
			);
		}

		return $return;
	}

/**
 * Fetch a feed and format the results
 *
 * @param string $slug
 * @param string $groupId
 *
 * @return array
 */
	public function getFeed($slug = null, $groupId = 999) {
		if (!$slug) {
			return array();
		}

		$mainFeed = $this->find(
			'first',
			array(
				'conditions' => array(
					'Feed.active' => 1,
					//'Feed.group_id > ' => $grouId,
					'Feed.slug' => $slug
				)
			)
		);

		if (empty($mainFeed)) {
			return array();
		}

		$items = ClassRegistry::init('Feed.FeedsFeed')->find(
			'list',
			array(
				'conditions' => array('FeedsFeed.main_feed_id' => $mainFeed['Feed']['id']),
				'fields' => array('FeedsFeed.sub_feed_id', 'FeedsFeed.sub_feed_id')
			)
		);

		if (empty($items)) {
			return array();
		}

		$items = $this->find(
			'all',
			array(
				'conditions' => array(
					'Feed.id' => $items,
					'Feed.active' => 1
				)
			)
		);


		if (empty($items)) {
			return array();
		}

		foreach ($items as $item) {
			$mainFeed['FeedItem'][] = $item['Feed'];
		}

		return $this->feedArrayFormat($this->getJsonRecursive($mainFeed));
	}

/**
 * Fetch a feed
 *
 * @param array $feed the feed query options
 *
 * @return array
 */
	public function feedArrayFormat($feed = array()) {
		if (empty($feed)) {
			return array();
		}
		$query = array();
		$query['fields']	 = $feed['Feed']['fields'];
		//$query['conditions'] = $feed['Feed']['conditions'];
		//$query['order']	  = $feed['Feed']['order'];
		$query['limit']	  = $feed['Feed']['limit'];
		$query['setup']	  = array(
			'plugin' => $feed['Feed']['plugin'],
			'controller' => $feed['Feed']['controller'],
			'action' => $feed['Feed']['action']
		);

		foreach ($feed['FeedItem'] as $item) {
			$plugin = Inflector::camelize($item['plugin']);
			$controller = Inflector::camelize(Inflector::singularize($item['controller']));
			$query['feed'][$plugin.'.'.$controller] = array(
				'setup' => array(
					'plugin' => $item['plugin'],
					'controller' => $item['controller'],
					'action' => $item['action']
				),
				'fields'	 => $item['fields'],
				//'conditions' => $item['conditions'],
				//'limit'	  => $item['limit']
			);
		}

		$_Model = ClassRegistry::init($feed['Feed']['plugin'] . '.' . Inflector::camelize(Inflector::singularize($feed['Feed']['controller'])));

		return $_Model->find('feed', $query);
	}

}