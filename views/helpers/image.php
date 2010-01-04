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

        var $places = null;
        var $images = null;

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

        function getRelativePath( $places = null, $key = null )
        {
            $places = $this->__placeExists( $places );

            if ( !$places )
            {
                return $places;
            }

            if ( !$key )
            {
                $this->errors[] = 'No key or place given to find a path';
            }

            foreach( $this->__getImages() as $path => $image )
            {
                $return = $this->__imageExists( $path, $key, 'relativePath' );

                if ( $return !== false )
                {
                    return $return;
                }
            }
        }

        function __placeExists( $places = null )
        {
            if ( !is_array( $places ) )
            {
                $places = array( $places );
            }

            foreach( $places as $k => $place )
            {
                if ( !in_array( $place, $this->__getPlaces() ) )
                {
                    unset( $places[$k] );
                }
            }

            if ( empty( $places ) )
            {
                $this->errors[] = 'the place(s) does not exist.';
                return false;
            }

            return $places;
        }

        function __getImages()
        {
            if ( !$this->images )
            {
                $this->images = Configure::read( 'CoreImages.images' );
            }
            return $this->images;
        }

        function __getPlaces()
        {
            if ( !$this->places )
            {
                $this->places = array_keys( $this->__getImages() );
            }
            return $this->places;
        }

        function __imageExists( $place, $key, $returnType = null )
        {
            $images = $this->__getImages();

            if ( !isset( $images[$place][$key] ) )
            {
                $this->errors[] = 'CoreImages.images.'.$place.'.'.$key.' does not exist';
                return false;
            }

            switch( $returnType )
            {
                case 'fileName':
                    return $images[$place][$key];
                    break;

                case 'relativePath':
                    return Configure::read( 'CoreImages.path' ).$place.'/'.$images[$place][$key];
                    break;

                case 'absolutePath':
                    echo 'todo: '.__LINE__.' - '.__FILE__ ;
                    exit;
                    break;

                default:
                    return true;
            } // switch
        }
    }
?>