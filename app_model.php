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

class AppModel extends Model {
	var $useDbConfig = 'default';

	var $actsAs = array(
		'Containable', 'Core.Lockable'
		// 'Core.Logable' some wierd issues
		);

	function _findFeed(&$Model, $query) {
		if ( !isset( $query['feed'] ) )
            {
                return $query;
            }

            $sql = 'SELECT ';
            if ( isset( $query['setup'] ) )
            {
                $fields = array();

                foreach( $query['setup'] as $name => $value )
                {
                    $fields[] = "'$value' AS $name";
                }

                $sql .= implode( ', ', $fields );
            }

            if ( isset( $query['fields'] ) )
            {
                $_fields = array();

                foreach( $query['fields'] as $field )
                {
                    $__fields = explode( '.', $field );
                    $_fields[] = $__fields[1];
                }

                $sql .= ', '.implode( ', ', $_fields );
            }

            $sql .= ' FROM '.$this->tablePrefix.$this->useTable;

            if ( isset( $query['feed'] ) )
            {
                foreach( $query['feed'] as $key => $feed )
                {
                    $sql .= ' UNION SELECT ';
                    if ( isset( $feed['setup'] ) )
                    {
                        $fields = array();

                        foreach( $feed['setup'] as $name => $value )
                        {
                            $fields[] = "'$value' AS $name";
                        }

                        $sql .= implode( ', ', $fields );
                    }

                    if ( isset( $feed['fields'] ) )
                    {
                        $_fields = array();

                        foreach( $feed['fields'] as $field )
                        {
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

            if ( isset( $query['order'] ) )
            {
                $ordering = array();

                foreach( $query['order'] as $key => $value )
                {
                    $ordering[] = $key.' '.$value;
                }

                $sql .= ' ORDER BY '.implode( ', ', $ordering );
            }

            return $this->query( $sql );
        }

	function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
		switch($conditions) {
			case 'feed':
				return $this->_findFeed($this, $fields);
				break;

			default:
				return parent::find($conditions, $fields, $order, $recursive);
		} // switch
	}
}

?>