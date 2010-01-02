<?php
    /**
     * Blog right_boxes view element file.
     *
     * the notifications/overviews on the right hand side of the admin interface.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       blog
     * @subpackage    blog.views.elements.right_boxes
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    /**
     * Generate links for comments
     */
    $data[1]['plugin']        = 'management';
    $data[1]['controller']    = 'comments';
    $data[1]['action']        = 'index';
    $data[1]['Comment.class'] = 'Post';
    $data[2]                  = null;

    $total_comments = 0;

    foreach( $commentCount as $type => $count )
    {
        $data[0] = __( $type.' comments', true ).' ('.$count.')';
        $data[1]['Comment.active'] = ( $type ) ? 1 : 0;

        echo $this->Design->quickLink( $data );

        $total_comments = $total_comments + $count;
    }

    $data[0] = __( 'All Comments', true ).' ('.$total_comments.')';
    unset( $data[1]['Coment.active'] );

    echo $this->Design->quickLink( $data );

    /**
    * show a box with pending posts
    */
    echo $this->Design->infoBox(
        array(
            __( 'Pending Posts', true ),
            $postPending,
            null,
            array(
                'core/icons/actions/16/disabled.png',
                array(
                    'plugin' => 'blog',
                    'controller' => 'posts',
                    'action' => 'toggle'
                ),
                array(
                    'title' => __( 'Click to activate', true ),
                    'alt'   => __( 'Activate', true )
                )
            )
        )
    );

    /**
    * show a box with popular posts
    */
    echo $this->Design->infoBox(
        array(
            __( 'Popular Posts', true ),
            $postPopular,
            array(
                array(
                    'plugin'     => 'blog',
                    'controller' => 'posts',
                    'action'     => 'view',
                    'admin'      => false
                ),
                array(
                    'title' => __( 'Click to view', true ),
                    'alt'   => __( 'View', true )
                )
            ),
            null
        )
    );
?>