<?php
    /**
     * ImageHelper
     *
     * @package
     * @author dogmatic
     * @copyright Copyright (c) 2010
     * @version $Id$
     * @access public
     */
    class ImageHelper extends AppHelper
    {
        var $settings = array(
            'width' => '20px'
        );

        function image( $path = null, $key = null )
        {
            $images = Configure::read( 'CoreImages.images' );
            if ( !$path && !$key )
            {
                $this->errors[] = 'Give some data';
                return false;
            }

            if ( !$path )
            {
                return $this->findByExtention( $key );
            }


            return $this->Html->image(
                Configure::read( 'CoreImages.path' ).$path.'/'.$images[$path][$key],
                $this->settings + array(
                    'title' => Inflector::humanize( $key ),
                    'alt'   => Inflector::humanize( $key )
                )
            );
        }
        /**
         * ImageHelper::findByExtention()
         *
         * @param mixed $extention
         * @return
         */
        function findByExtention( $extention )
        {
            $images = Configure::read( 'CoreImages' );

            foreach( $images['images'] as $path => $image )
            {
                if ( isset( $image[$extention] ) )
                {
                    return $this->Html->image(
                        Configure::read( 'CoreImages.path' ).$path.'/'.$image[$extention],
                        $this->settings + array(
                            'title' => Inflector::humanize( $extention ),
                            'alt'   => Inflector::humanize( $extention )
                        )

                    );
                }
            }
        }

        /**
         * ImageHelper::findByExtention()
         *
         * @param mixed $extention
         * @return
         */
        function findByChildren( $children = array() )
        {
            $images = Configure::read( 'CoreImages.images' );
            if ( empty( $children[1] ) )
            {
                return $this->Html->image(
                    Configure::read( 'CoreImages.path' ).'folders/'.$images['folders']['empty'],
                    $this->settings + array(
                        'title' => __( 'Empty Folder', true ),
                        'alt'   => __( 'Empty Folder', true )
                    )
                );
            }

            App::import( 'File' );

            foreach( $children[1] as $child )
            {
                $File = new File( $child );
                $ext = $File->ext();

                if ( !isset( $data[$ext] ) )
                {
                    $data[$ext] = 0;
                    continue;
                }

                $data[$ext]++;
                unset( $File );
            }

            $highest = 0;
            $_ext = '';
            foreach( $data as $k => $v )
            {
                if ( $v > $highest )
                {
                    $highest = $v;
                    $_ext = $k;
                }
            }

            return $this->findByExtention( $_ext );
        }
    }
?>