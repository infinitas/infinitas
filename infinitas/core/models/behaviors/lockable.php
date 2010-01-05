<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    class LockableBehavior extends ModelBehavior
    {

        /**
         * Contain settings indexed by model name.
         *
         * @var array
         * @access private
         */
        var $__settings = array();

        /**
         * Initiate behavior for the model using specified settings. Available settings:
         *
         * - locked_by: 	int      field name of the peson that locked the field
         * - locked_since:  datetime field name of the date the record was locked.
         * - locked:        bool     field name of the locked status field.
         *
         * @param object $Model Model using the behaviour
         * @param array $settings Settings to override for model.
         * @access public
         */
        function setup(&$Model, $settings = array())
        {
            $default = array(
                'locked_by' => 'locked_by',
                'locked_since' => 'locked_since',
                'locked' => 'locked'
            );

            if ( !isset( $this->__settings[$Model->alias] ) )
            {
                $this->__settings[$Model->alias] = $default;
            }

            $this->__settings[$Model->alias] = am(
                $this->__settings[$Model->alias],
                ife( is_array( $settings ), $settings, array() )
            );
        }

        function read( $fields, $id )
        {
            pr( 'lockable read' );
            exit;
        }
    }
?>