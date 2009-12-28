<?php
    /**
     * Blog Comments Controller class file.
     *
     * This is the main controller for all the blog comments.  It extends
     * {@see BlogAppController} for some functionality.
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
     * @subpackage    blog.controllers.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    class CommentsController extends BlogAppController
    {
        var $name = 'Comments';

        var $uses = array( 'Core.Comment' );

        function admin_index( $active = null )
        {
            $conditions = array();
            if ( $active !== null )
            {
                $conditions = array( 'Comment.active' => $active );
            }

            $this->paginate = array(
                'fields' => array(
                    'Comment.id',
                    'Comment.name',
                    'Comment.email',
                    'Comment.website',
                    'Comment.comment',
                    'Comment.active',
                    'Comment.foreign_id',
                    'Comment.created',
                ),
                'conditions' => $conditions,
                'order' => array(
                    'Comment.active' => 'ASC',
                    'Comment.created' => 'ASC',
                ),
                'limit' => 20,
                'Comment' => array(
                    'contain' => array(
                        'Post' => array(
                            'fields' => array(
                                'Post.title',
                                'Post.slug'
                            )
                        )
                    )
                )
            );

            $comments = $this->paginate( 'Comment' );

            $this->set( compact( 'comments' ) );
        }

        function admin_perge( $date = null )
        {
            if ( !$date )
            {
                $date = date( 'Y-m-d h:i:s', mktime( 0, 0, 0, date( 'm' ) -1, date( 'd' ), date( 'y' ) ) );
            }

            $old = $this->Comment->find(
                'list',
                array(
                    'fields' => array(
                        'Comment.id',
                        'Comment.id'
                    ),
                    'conditions' => array(
                        'Comment.created < ' => $date,
                        'Comment.active' => 0
                    ),
                    'contain' => false
                )
            );

            if ( empty( $old ) )
            {
                $this->Session->setFlash( __( 'No old comments found', true ) );
                $this->redirect( $this->referer() );
            }

            $i = 0;
            foreach( $old as $id )
            {
                if ( $this->Comment->delete( $id ) )
                {
                    $i++;
                }
            }

            $this->Session->setFlash( sprintf( '%s %s', $i, __( 'Comments deleted', true ) ) );
        }
    }
?>