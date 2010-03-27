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

	function _findFeed(&$Model, $state, $query, $results = array()) {
		if($state == 'before') {
			if ( !isset( $query['feed'] ) ) {
					return $query;
			}
			$sql = 'SELECT ';
   			if ( isset( $query['setup'] ) ) {
	   			$fields = array();
				foreach( $query['setup'] as $name => $value ) {
					$fields[] = "'$value' AS $name";
				}
				$sql .= implode( ', ', $fields );
			}
			if ( isset( $query['fields'] ) ) {
				$_fields = array();
				foreach( $query['fields'] as $field ) {
					$__fields = explode( '.', $field );
					$_fields[] = $__fields[1];
				}
				$sql .= ', '.implode( ', ', $_fields );
			}
			$sql .= ' FROM '.$Model->tablePrefix.$Model->useTable;
			if ( isset( $query['feed'] ) ) {
				foreach( $query['feed'] as $key => $feed ) {
					$sql .= ' UNION SELECT ';
					if ( isset( $feed['setup'] ) ) {
						$fields = array();
						foreach( $feed['setup'] as $name => $value ) {
							$fields[] = "'$value' AS $name";
						}
						$sql .= implode( ', ', $fields );
					}
					if ( isset( $feed['fields'] ) ) {
						$_fields = array();
						foreach( $feed['fields'] as $field ) {
							$__fields = explode( '.', $field );
							$_fields[] = $__fields[1];
						}
						$sql .= ', '.implode( ', ', $_fields );
					}
					$__key = explode( '.', $key );
					$__key[0] = strtolower( $__key[0] );
				   	$__key[1] = strtolower( Inflector::pluralize( $__key[1] ) );
				   	$sql .= ' FROM '.implode( '_', $__key );

					if ( isset( $feed['conditions'] ) ) {
						$_fields = array();
						foreach( $feed['conditions'] as $field => $condition ) {
							if (is_string($field)) {
								$__fields = explode( '.', $field );
								$_fields[] = $__fields[1].' = '.$condition;
							}
							else{
								$__fields = explode( '.', $condition );
								$_fields[] = $__fields[1];
							}
						}

						$sql .= ' WHERE ';
						$sql .= implode( ' AND ', $_fields );
					}
			   	}
			}
			if ( isset( $query['order'] ) ) {
				$ordering = array();
				foreach( $query['order'] as $key => $value ) {
					$ordering[] = $key.' '.$value;
				}
				$sql .= ' ORDER BY '.implode( ', ', $ordering );
			}
			if ( isset( $query['limit'] ) ) {
				$ordering = array();
				$sql .= ' LIMIT '.$query['limit'];
			}
			$_results = $Model->query( $sql );
			//pr( $this->_results );

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
?>