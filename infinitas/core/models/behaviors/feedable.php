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
* @link http://www.dogmatic.co.za
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class FeedableBehavior extends ModelBehavior {
	
	var $_defaults = array();
/**
 * @param object $Model Model using the behavior
 * @param array $settings Settings to override for model.
 * @access public
 * @return void
 */
	function setup(&$Model, $config = null) {
		$Model->_findMethods = array_merge($Model->_findMethods, array('feeds'=>true));
		if (is_array($config)) {
			$this->settings[$Model->alias] = array_merge($this->_defaults, $config);            
		} else {
			$this->settings[$Model->alias] = $this->_defaults;
		}
		$Model->Behaviors->__methods = array_merge(
			$Model->Behaviors->__methods,
			array('_findFeeds' => array('_findFeeds', 'Feedable'))
		);
	}
	
	function _findFeeds(&$Model, $state, $query, $results = array()) {
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
                }
            }
            if ( isset( $query['order'] ) ) {
                $ordering = array();
                foreach( $query['order'] as $key => $value ) {
                    $ordering[] = $key.' '.$value;
                }
                $sql .= ' ORDER BY '.implode( ', ', $ordering );
            }
            return $Model->query( $sql );
        }
}
?>