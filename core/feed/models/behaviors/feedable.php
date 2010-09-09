<?php
/**
	* Comment Template.
	*
	* @todo Implement .this needs to be sorted out.
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	* @filesource
	* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link http://infinitas-cms.org
	* @package sort
	* @subpackage sort.comments
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.5a
	*/

	class FeedableBehavior extends ModelBehavior {

		var $_defaults = array();

		var $_results = null;

		var $basicStatement = array(
			'order' => array(),
			'limit' => array(),
			'setup' => array(),
			'conditions' => array(),
			'joins' => array(),
			'group' => array(),
			'fields' => array(),

		);
	/**
	 * @param object $Model Model using the behavior
	 * @param array $settings Settings to override for model.
	 * @access public
	 * @return void
	 */
		function setup(&$Model, $config = null) {
			$Model->_findMethods = array_merge($Model->_findMethods, array('feed'=>true));
			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			} else {
				$this->settings[$Model->alias] = $this->_defaults;
			}
			$Model->Behaviors->__methods = array_merge(
				$Model->Behaviors->__methods,
				array('_findFeed' => array('_findFeed', 'Feedable'))
			);
		}

		function _findFeed(&$Model, $state, $query, $results = array()){
			if($state == 'before') {
				if (!isset($query['feed'])){
					return $query;
				}

				$DboMysql = Connectionmanager::getDataSource($Model->useDbConfig);

				$sql = '';
				foreach((array)$query['feed'] as $key => $feed){
					$feed = array_merge($this->basicStatement, $feed);
					$sql .= ' UNION ';

					$currentModel = ClassRegistry::init($key);

					$setup = explode(' AND ', str_replace(array('=', '`'), array('AS', '\''), $DboMysql->conditions(array_flip($feed['setup']), false, false)));
					$sql .= $DboMysql->renderStatement(
						'select',
						array(
							'fields' => implode(', ', array_merge($DboMysql->fields($currentModel, null, (array)$feed['fields']), $setup)),
							'table' => $DboMysql->fullTableName($currentModel),
							'alias' => $currentModel->alias,
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
						'alias' => $Model->alias,
						'joins' => '',
						'conditions' => $DboMysql->conditions($query['conditions']),
						'group' => $sql, // @todo slight hack
						'order' => $DboMysql->order($query['order']),
						'limit' => $DboMysql->limit($query['limit'])
					)
				);

				$_results = $Model->query( $sql );

				foreach( $_results as $res ){
					$this->_results[]['Feed'] = $res[0];
				}
				return $query;
			} elseif ($state == 'after') {
				return $this->_results;
			}
			return false;
		}
	}