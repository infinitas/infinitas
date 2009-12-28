<?php
    /**
     *
     *
     */
    /**
     * AppModel
     *
     * @package
     * @author dogmatic
     * @copyright Copyright (c) 2009
     * @version $Id$
     * @access public
     */
    class AppModel extends Model
    {
        var $actsAs = array( 'Containable' );

        var $useDbConfig = 'default';


        function lock( $fields = null, $id = null )
        {
            $old_recursive = $this->recursive;

            $this->recursive = -1;
            $data = parent::read( array( 'locked', 'locked_by', 'id' ), $id );

            $this->Session = new CakeSession();

            if ( $data[$this->name]['locked'] && $data[$this->name]['locked_by'] != $this->Session->read( 'Auth.User.id' ) )
            {
                return false;
            }

            $data[$this->name]['locked'] = 1;
            $data[$this->name]['locked_by'] = $this->Session->read( 'Auth.User.id' );
            $data[$this->name]['locked_since'] = date( 'Y-m-d H:i:s' );

            parent::save( $data, array( 'validation' => false ) );

            $this->recursive = $old_recursive;
            $data = $this->read( $fields, $id );
            return $data;
        }

        /**
         * For unlocking records.
         *
         * sets the lock to false and  sets the date and person to null
         *
         * @param array $data
         * @param array $options
         * @return {@see Model::save}
         */
        function save( $data = null, $validate = true, $fieldList = array() )
        {
            $data[$this->name]['locked'] = 0;
            $data[$this->name]['locked_by'] = null;
            $data[$this->name]['locked_since'] = null;

            return parent::save( $data, $validate, $fieldList );
        }

        function find( $conditions = null, $fields = array(), $order = null, $recursive = null )
        {
            switch( $conditions )
            {
                case 'feed':
                    return $this->__feedableFind( $this, $fields );
                    break;

                default:
                    return parent::find( $conditions, $fields, $order, $recursive );
            } // switch
        }

        function __feedableFind(&$Model, $query)
        {
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

            $sql .= ' FROM '.$Model->tablePrefix.$Model->useTable;

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
    }
?>