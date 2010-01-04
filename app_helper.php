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

    class AppHelper extends Helper
    {
        var $rowClassCounter = 0;

        var $paginationCounterFormat = 'Page %page% of %pages%.';

        var $errors = array();

        var $wysiwyg = 'fck';

        var $helpers = array( 'Html', 'Core.Wysiwyg', 'Core.Gravatar' );

        /**
        * create some bread crumbs.
        *
        * Creates some bread crumbs.
        *
        * @todo -cAppHelper Implement AppHelper.
        * - generate some links
        *
        * @param array $here is $this from the view.
        */
        function breadcrumbs( $view = array(), $seperator = ' :: ' )
        {
            if ( empty( $view ) )
            {
                return false;
            }

            $breadcrumbs = array(
                $view->params['prefix'],
                $view->params['plugin'],
                $view->name
            );

            return implode( $seperator, $breadcrumbs );
        }

        /**
         * switch the class for table rows
         *
         * @param integer $i the number of the row
         * @param string $class1 class 1 highlight
         * @param string $class2 class 2 highlight
         * @return string the class
         */
        function rowClass( $class1 = 'bg', $class2 = '' )
        {
            return ( ( $this->rowClassCounter++ % 2 ) ? $class1 : $class2 );
        }

        function adminPageHead( $view )
        {
            if ( empty( $view ) )
            {
                return false;
            }

            return '<div class="top-bar"><h1>'.__( $view->name, true ).'</h1>'.
                '<div class="breadcrumbs">'.$this->breadcrumbs( $view ).'</div></div>';
        }

        /**
        * creates table headers for admin.
        *
        * if the format is just array( 'head1', 'head2' ... ) it will output a
        * normal table with TH that have no classes/styles applied.  you can
        * also pass things like array ( 'head1' => array( 'class' => 'something' ) )
        * to get out put like <th class="something">head1</th>
        *
        * @param array $data an array of items for the head.
        */
        function adminTableHeader( $data )
        {
            $out = '<tr>';
                foreach( $data as $field => $params )
                {
                    $atributes = '';

                    if ( is_int( $field ) && !is_array( $params ) )
                    {
                        $field = $params;
                        $params = '';
                    }

                    else
                    {

                        foreach( $params as $type => $param )
                        {
                            $atributes = '';
                            $atributes .= $type.'="'.$param.'" ';
                        }

                        $atributes .= $atributes;
                    }

                    if ( $atributes != '' )
                    {
                        $params = $atributes;
                    }

                    $out .= '<th '.$params.'>'.$field.'</th>';
                }
            $out .= '</tr>';

            return $out;
        }

        /**
        * creates the header images for the admin table headers.
        */
        function adminTableHeadImages()
        {

            return $this->Html->image(
                'admin/bg-th-left.gif',
                array(
                    'width' => '8px',
                    'height' => '7px',
                    'class' => 'left'
                )
            ).
            $this->Html->image(
                'admin/bg-th-right.gif',
                array(
                    'width' => '8px',
                    'height' => '7px',
                    'class' => 'right'
                )
            );
        }

        function adminIndexHead( $view = array(), $pagintion = array(), $filterOptions = array() )
        {
            if ( empty( $view ) )
            {
                $this->errors[] = 'I need the view.';
                return false;
            }

            $filters = '';
            if ( !empty( $filterOptions ) )
            {
                App::import( 'Helper', 'FilterHelper' );
                $filters = FilterHelper::clear( $filterOptions );
            }

            $out = '<div class="adminTopBar">';
                $out .= $this->adminPageHead( $view );
                $out .= '<div class="main-actions">';
                $out .= $this->Html->link(
                    'Add',
                    array(
                        'action' => 'add'
                    )
                );
                $out .= '</div>';
            $out .= '</div>';

            return $out.$filters;
        }

        function adminOtherHead( $view = array() )
        {
            if ( empty( $view ) )
            {
                $this->errors[] = 'I need the view.';
                return false;
            }

            $out = '<div class="adminTopBar">';
                $out .= $this->adminPageHead( $view );
                $out .= '<div class="main-actions">';
                    $out .= $this->Html->link(
                        'Index',
                        array(
                            'action' => 'index'
                        )
                    ).' ';
                    $out .= $this->Html->link(
                        'Add',
                        array(
                            'action' => 'add'
                        )
                    );
                $out .= '</div>';
            $out .= '</div><div class="clr">&nbsp;</div>';

            return $out;
        }

        function ordering( $id = null, $order = null )
        {
            if ( !$id )
            {
                $this->errors[] = 'You need an id to move something';
                return false;
            }

            if ( !$order )
            {
                $this->errors[] = 'The order was not passed';
            }
            $out  = $order.' ';

            $out .= $this->Html->link(
                $this->Html->image(
                    'core/icons/actions/16/arrow-up-yes.png'
                ),
                array(
                    'action' => 'reorder',
                    $id,
                    'direction' => 'up',
                    'amount' => 1
                ),
                array(
                    'escape' => false,
                    'title' => __( 'Move up', true ),
                    'alt' => __( 'Up', true )
                )
            );

            $out .= $this->Html->link(
                $this->Html->image(
                    'core/icons/actions/16/arrow-down-yes.png'
                ),
                array(
                    'action' => 'reorder',
                    $id,
                    'direction' => 'down',
                    'amount' => 1
                ),
                array(
                    'escape' => false,
                    'title' => __( 'Move down', true ),
                    'alt' => __( 'Down', true )
                )
            );

            return $out;
        }

        function paginationCounter( $pagintion )
        {
            if ( empty( $pagintion ) )
            {
                $this->errors[] = 'You did not pass the pagination object.';
                return false;
            }

            $out = '';
                $out .= $pagintion->counter( array( 'format' => __( $this->paginationCounterFormat, true ) ) );
            $out .= '';

            return $out;
        }

        function wysiwyg( $id = null, $toolbar = 'Basic' )
        {
            if ( !$id )
            {
                $this->errors[] = 'No field specified for the wysiwyg editor';
                return false;
            }

            if ( !Configure::read( 'Wysiwyg.editor' ) )
            {
                $this->errors[] = 'There is no editor configured';
                return false;
            }

            $editor = ( Configure::read( 'Wysiwyg.editor' ) ) ? Configure::read( 'Wysiwyg.editor' ) : 'text';

            return $this->Wysiwyg->$editor( $id, $toolbar );
        }

        function gravatar( $email = null, $options = array() )
        {
            if ( !$email )
            {
                $this->errors[] = 'no email specified for the gravatar.';
                return false;
            }

            return $this->Gravatar->image( $email, $options );
        }

        function niceBox( $class = '', $content = false )
        {
            $out = '<div class="niceBox '.$class.'"><div class="top"><div class="top"><div class="top"></div></div></div>';
                $out .= '<div class="middle">';
                    if ( $content )
                    {
                        $out .= $content.'<div class="clr"></div>';
                    }
                $out .= '</div>';
                if ( $content )
                {
                    $out .= $this->niceBoxEnd();
                }
            return $out;
        }

        function niceBoxEnd()
        {
            return '<div class="bottom"><div class="bottom"><div class="bottom"></div></div></div></div>';
        }

        function massActionButtons( $buttons = null )
        {
            if ( !$buttons )
            {
                $this->errors[] = 'No buttons set';
                return false;
            }

            $out = '';

            foreach( $buttons as $button )
            {
                $out .= $this->Form->button( __( Inflector::humanize( $button ), true ), array( 'value' => strtolower( str_replace( array( '-', ' ' ), '_', $button ) ), 'name' => 'action' ) );
            }

            return $out;
        }
    }
?>