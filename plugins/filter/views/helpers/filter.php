<?php
    class FilterHelper extends Helper
    {
    	var $helpers = array(
    	    'Form', 'Html'
    	);

        var $count = 0;

    	function form( $model, $fields = array() )
    	{
    		$output  = '<tr>';
        		$output .= $this->Form->create( $model, array( 'action' => 'index', 'id' => 'filters' ) );

        		foreach( $fields as $field )
        		{
        			if( empty( $field ) )
        			{
        				$output .= '<th>&nbsp;</th>';
        			}

                    else
                    {
        				$output .= '<th>' . $this->Form->input($field, array('label' => false)) . '</th>';
        			}
        		}

        		$output .= '<th>'.
        		    $this->Form->button( __( 'Filter', true ), array( 'type' => 'submit', 'name' => 'data[filter]' ) ).
        		    $this->Form->button( __( 'Reset', true ), array( 'type' => 'submit', 'name' => 'data[reset]' ) ).
        		'</th>';

        		$output .= $this->Form->end();
    		$output .= '</tr>';
    		return $output;
    	}

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
        function clear( $filter, $div = false )
        {
            if ( !isset( $filter['url'][0] ) || empty( $filter['url'][0] ) || $filter['url'][0] == '/' )
            {
                $filter['url'][0] = '/';
            }

            $out = '';
                if ( $div )
                {
                    $out .= '<div class="filter">';
                }
                $out .= '<div class="wrap">';
                    $parts = explode( '/', $filter['url'][0] );

                    $done = array();

                    foreach( $parts as $_f )
                    {
                        if ( empty( $_f ) || in_array( $_f, $done ) )
                        {
                            continue;
                        }

                        $done[] = $_f;

                        $text = explode( ':', $_f );
                        $text = explode( '.', $text[0] );
                        $text = ( count( $text ) > 1 ) ? $text[1] : $text[0];

                        $out .= '<div class="left">'.
                                    '<div class="remove">'.
                                        $this->Html->link(
                                            Inflector::humanize( $text ),
                                            str_replace( $_f, '', $this->here )
                                        ).
                                    '</div>'.
                                '</div>';
                    }
                $out .= '</div>';
                if ( $div )
                {
                    $out .= '</div>';
                }

            return $out;
        }
    }
?>