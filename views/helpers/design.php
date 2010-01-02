<?php
    /**
     *
     *
     */
    class DesignHelper extends AppHelper
    {
        var $helpers = array( 'Text', 'Html' );

        function infoBox( $info = array(), $truncate = 24 )
        {
            list( $heading, $data, $link, $image ) = $info;

            if ( empty( $data ) )
            {
                $this->errors[] = 'No data supplied';
                return false;
            }

            $out = '<div class="infoBox">';
                $out .= '<div class="heading">'.$heading.'</div>';
                $out .= '<div class="body">';
                    foreach( $data as $id => $text )
                    {
                        if ( $text == '' || empty( $text ) || !$text )
                        {
                            continue;
                        }

                        $text = $this->Text->truncate( $text, $this->__adjustTruncate( $truncate, $image, $link ) );

                        if ( $image )
                        {
                            list( $image_path, $url, $params ) = $image;
                            $url = $this->__createUrl( $url, $id );

                            $url_params = $params;
                            unset( $url_params['alt'] );

                            $out .= $this->Html->link(
                                $this->Html->image( $image_path, $params ).' '.$text,
                                $url,
                                $url_params + array( 'escape' => false )
                            );
                        }
                        else if ( $link )
                        {
                            list( $url, $params ) = $link;
                            $url = $this->__createUrl( $url, $id );

                            unset( $params['alt'] );
                            $out .= $this->Html->link(
                                $text,
                                $url,
                                $params
                            );
                        }
                        else if ( $data )
                        {
                            $out .= $text;
                        }
                    }
                $out .= '</div>';
            $out .= '</div>';

            return $out;
        }

        function quickLink( $info = array(), $truncate = 24 )
        {
            if ( count( $info ) < 2 || empty( $info ) )
            {
                $this->errors[] = 'No data to generate links';
                return false;
            }

            list( $link, $url, $params ) = $info;

            $link = $this->__niceText( $link, $truncate );

            if ( empty( $link ) )
            {
                $this->errors[] = 'No text for the link supplied';
                return false;
            }

            return $this->Html->link(
                $link,
                $url,
                (array)$params + array( 'class' => 'quickLink' )
            );


        }

        function __createUrl( $url = null, $id = 0 )
        {
            if ( !$url )
            {
                $this->errors[] = 'No url passed, returning root';
                return '/';
            }

            if ( is_array( $url ) )
            {
                $url = $url + array( $id );
            }
            else
            {
                $url = rtrim( $url, '/' );
                $url .= '/'.$id;
            }

            return $url;
        }

        function __niceText( $text = '', $truncate = false )
        {
            $text = Inflector::humanize( $text );
            if ( $truncate )
            {
                $text = $this->Text->truncate( $text, $truncate );
            }

            return $text;
        }

        function __adjustTruncate( $truncate = false, $image = false, $link = false )
        {
            if ( !$truncate )
            {
                return $truncate;
            }

            // decrease a bit for the image
            if ( $image )
            {
                $truncate = $truncate - 5;
            }

            // increase a bit cos there is no link
            else if ( !$link )
            {
                $truncate = $truncate + 5;
            }

            return $truncate;
        }
    }
?>