<?php
    class FilterHelper extends Helper
    {
    	var $helpers = array(
    	    'Form', 'Html'
    	);

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
        function clear( $filter )
        {
            $out = '<div class="filter">'.
                '<div class="wrap">';
                    $parts = explode( '/', $filter['url'][0] );

                    foreach( $parts as $_f )
                    {
                        if ( empty( $_f ) )
                        {
                            continue;
                        }

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
                $out .= '</div>'.
                '</div>';

            return $out;
        }
    }
?>