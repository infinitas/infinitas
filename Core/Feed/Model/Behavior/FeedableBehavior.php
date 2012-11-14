<?php
/**
 * FeedableBehavior
 *
 * @package Infinitas.Feed.Model.Behavior
 */

/**
 * FeedableBehavior
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Feed.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 * @author Ceeram
 */

class FeedableBehavior extends ModelBehavior {
/**
 * Behavior defaults
 *
 * @var array
 */
	protected $_defaults = array();

/**
 * Internal cache of results
 *
 * @var array
 */
	protected $_results = null;

/**
 * Basic cake find options
 *
 * @var array
 */
	public $basicStatement = array(
		'order' => array(),
		'limit' => array(),
		'setup' => array(),
		'conditions' => array(),
		'joins' => array(),
		'group' => array(),
		'fields' => array(),

	);

/**
 * Setup the behavior
 *
 * @param Model $Model Model using the behavior
 * @param array $settings Settings to override for model.
 *
 * @return void
 */
	public function setup(Model $Model, $config = null) {
		$Model->findMethods = array_merge($Model->findMethods, array('feed'=>true));

		$this->settings[$Model->alias] = $this->_defaults;
		if (is_array($config)) {
			$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $config);
		}

		$Model->findMethods['findFeed'] = true;
	}

/**
 * Custom find('feed') method
 *
 * Fetches data from many models as a single record set, being able to order by
 * a created field across all the different tables in one query
 * 
 * @param Model $Model
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findFeed(Model $Model, $state, $query, $results = array()) {
		if($state == 'before') {
			if (!isset($query['feed'])) {
				return $query;
			}

			$DboMysql = Connectionmanager::getDataSource($Model->useDbConfig);


			$sql = '';
			foreach((array)$query['feed'] as $key => $feed) {
				$feed = array_merge($this->basicStatement, $feed);
				$sql .= ' UNION ';

				$currentModel = ClassRegistry::init($key);

				$setup = explode(' AND ', str_replace(array('=', '`'), array('AS', '\''), $DboMysql->conditions(array_flip($feed['setup']), false, false)));
				$sql .= $DboMysql->renderStatement(
					'select',
					array(
						'fields' => implode(', ', array_merge($DboMysql->fields($currentModel, null, (array)$feed['fields']), $setup)),
						'table' => $DboMysql->fullTableName($currentModel),
						'alias' => ' AS ' . $currentModel->alias,
						'joins' => '',
						'conditions' => $DboMysql->conditions($feed['conditions']),
						'group' => '',
						'order' => $DboMysql->order($feed['order']),
						'limit' => $DboMysql->limit($feed['limit'])
					)
				);
			}

			$query = array_merge($this->basicStatement, $query);
			$setup = explode(' AND ', str_replace(array('=', '`'), array('AS', '\''), $DboMysql->conditions(array_flip($query['setup']), false, false)));
			$sql = $DboMysql->renderStatement(
				'select',
				array(
					'fields' => implode(', ', array_merge($DboMysql->fields($Model, null, (array)$query['fields']), $setup)),
					'table' => $DboMysql->fullTableName($Model),
					'alias' => ' AS '.$Model->alias,
					'joins' => '',
					'conditions' => $DboMysql->conditions($query['conditions']),
					'group' => $sql, // @todo slight hack
					'order' => $DboMysql->order($query['order']),
					'limit' => $DboMysql->limit($query['limit'])
				)
			);

			$_results = $Model->query($sql);

			foreach($_results as $res) {
				$this->_results[]['Feed'] = $res[0];
			}

			return $query;
		}

		return $this->_results;
	}

}