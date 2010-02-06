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
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    class TagCloudHelper extends Helper
    {
        /**
        * Cake helpers needed to generate the tags
        *
        * @param array
        */
        //var $helpers = array( 'Html' );

        var $config = array(
            'model' => 'Model',
            'primaryKey' => 'id',
            'count_field' => 'count',
            'displayField' => null,
            'url' => array(),
            'factor' => 1.4,
            'tag' => 'div',
            'class' => 'tag_item',
        );

        /**
        * The data being returned
        *
        * @parma array
        */
        var $return = null;

        /**
        * Display the tags
        *
        * Takes an array from find( 'all' ) and works out sizes to generate the
        * tag cloud.
        *
        * @param array $data from find( 'all' )
        * @param array $config settings for the tags
        *
        * @retrurn string the stags nicly formated
        */
        function display( $data, $config = array() )
        {
            $this->return = null;

            if ( !is_array( $data ) )
            {
                $this->errors[] = __( 'You need to pass some data', true );
                return false;
            }

            $this->config = array_merge( $this->config, (array)$config );

            $data = $this->score( $data );

            foreach( $data as $k => $v )
            {
                $this->return[] .=
                    '<'.$this->config['tag'].' '.
                        // html params
                        'title="'.$v[$this->config['model']]['name'].' ( '.$v[$this->config['model']][$this->config['count_field']].' '.
                        $t = (
                            ( $v[$this->config['model']][$this->config['count_field']] == 1)
                            ? $this->config['model']
                            : Inflector::pluralize( $this->config['model'] )
                        ).' )" '.
                        'style="font-size:'.$v[$this->config['model']]['score'].'%;" '.
                        'class="'.$this->config['class'].'">'.

                        // link for the tag
                            $this->Html->link(
                                $v[$this->config['model']][ClassRegistry::init( $this->config['model'] )->displayField],
                                (array)$this->config['url'] + (array)$v[$this->config['model']][ClassRegistry::init( $this->config['model'] )->displayField]
                            ).
                        // close
                    '</'.$this->config['tag'].'>';
            }

            shuffle( $this->return );

            return implode( '&nbsp;', $this->return );
        }

        /**
        * Calculate a score for the tag.
        *
        * calculates a score out of 100 to be used as a % for font-size. first
        * removes duplicate numbers to get the min and max of the count_field
        * after that works out a percentage.
        *
        * @param array $data from the display method
        * @param array $data from the display method
        *
        * @return array $data the data with an added 'score' field
        */
        function score( $data )
        {
            $counts =
                array_flip(
                    array_flip(
                        Set::extract( '/'.$this->config['model'].'/'.$this->config['count_field'], $data )
                    )
                );

            $min = min( $counts );
            $max = max( $counts );

            if ( $max < 1 )
            {
                $max = 1;
            }

            foreach( $data as $k => $v )
            {
                $data[$k][$this->config['model']]['score'] =
                (
                    ( $v[$this->config['model']][$this->config['count_field']] / $max ) * 100
                ) * $this->config['factor'];
            }

            return $data;
        }
    }
?>