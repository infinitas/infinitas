<?php
    /**
     * Blog Posts Controller class file.
     *
     * This is the main controller for all the blog posts.  It extends
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
     * @subpackage    blog.controllers.posts
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    class PostsController extends BlogAppController
    {
        var $name = 'Posts';

        var $helpers = array( 'Core.Geshi' );

        function beforeFilter()
        {
            parent::beforeFilter();

            /**
            * @todo -cPostsController Implement PostsController.beforeFilter
            * - unBind Viewable for admin
            */
        }

        function index( $tag = null, $year = null, $month = null )
        {
            $post_ids = '';
            if ( isset( $tag ) && strtolower( $tag ) != 'all' )
            {
                $post_ids = $this->Post->Tag->findPostsByTag( $tag );
            }

            $this->paginate = array(
                'fields' => array(
                    'Post.id',
                    'Post.title',
                    'Post.slug',
                    'Post.body',
                    'Post.intro',
                    'Post.comment_count',
                    'Post.created',
                ),
                'conditions' => array(
                    'Post.active' => 1,
                    'Post.id' . ( ( !empty( $post_ids ) ) ? ' IN ('.implode( ',', $post_ids ).')'  : ' > 0' )
                ),
                'contain' => array(
                    'Tag' => array(
                        'fields' => array(
                            'Tag.name'
                        )
                    ),
                    'Comment' => array(
                        'conditions' => array(
                            'Comment.active' => 1
                        )
                    )
                )
            );

            $posts = $this->paginate( 'Post' );
            $this->set( compact( 'posts' ) );
        }

        function view( $slug = null )
        {
            if ( !$slug )
            {
                $this->Session->setFlash( 'That post could not be found', true );
                $this->redirect( $this->referer() );
            }

            $post = $this->Post->find(
                'first',
                array(
                    'fields' => array(
                        'Post.id',
                        'Post.title',
                        'Post.slug',
                        'Post.intro',
                        'Post.body',
                        'Post.active',
                        'Post.views',
                        'Post.comment_count',
                        'Post.created',
                    ),
                    'conditions' => array(
                        'or' => array(
                            'Post.slug' => $slug,
                            'Post.id' => $slug
                        ),
                        'Post.active' => 1
                    ),
                    'contain' => array(
                        'Comment' => array(
                            'fields' => array(
                                'Comment.id',
                                'Comment.name',
                                'Comment.email',
                                'Comment.website',
                                'Comment.comment',
                                'Comment.created'
                            ),
                            'conditions' => array(
                                'Comment.active' => 1
                            )
                        )
                    )
                )
            );

            if ( empty( $post ) || !$post['Post']['active'] )
            {
                $this->Session->setFlash( 'No post was found', true );
                $this->redirect( $this->referer() );
            }

            $this->set( compact( 'post' )  );
            $this->set( 'title_for_layout', $post['Post']['slug'] );
        }



        /**
        * Admin Section
         */

        function admin_dashboard()
        {
            $feed = $this->Post->find(
                'feed',
                array(
                    'setup' => array(
                        'plugin' => 'Blog',
                        'controller' => 'posts',
                        'action' => 'view',
                    ),
                    'fields' => array(
                        'Post.id',
                        'Post.title',
                        'Post.intro',
                        'Post.created'
                    ),
                    'feed' => array(
                        'Core.Comment' => array(
                            'setup' => array(
                                'plugin' => 'Core',
                                'controller' => 'comments',
                                'action' => 'view',
                            ),
                            'fields' => array(
                                'Comment.id',
                                'Comment.name',
                                'Comment.comment',
                                'Comment.created'
                            )
                        )
                    ),
                    'order' => array(
                        'created' => 'DESC'
                    )
                )
            );

            $this->set( 'blogFeeds', $feed );

            $this->set( 'dashboardPostCount', $this->Post->getCounts() );
            $this->set( 'dashboardPostLatest', $this->Post->getLatest() );
            $this->set( 'dashboardCommentsCount', $this->Post->Comment->getCounts( 'Blog.Post' ) );
        }

        function admin_index( $active = null )
        {
            $conditions = array();
            if ( $active !== null )
            {
                $conditions = array( 'Post.active' => $active );
            }

//            $this->Post->recursive = 0;
//
//            $this->paginate = array(
//                'fields' => array(
//                    'Post.id',
//                    'Post.title',
//                    'Post.slug',
//                    'Post.intro',
//                    'Post.active',
//                    'Post.views',
//                    'Post.comment_count',
//                    'Post.created',
//                    'Post.locked',
//                    'Post.locked_by',
//                    'Post.locked_since',
//                ),
//                'conditions' => $conditions
//            );

            $this->Post->recursive = 0;
            $this->set( 'posts', $this->paginate() );

            //$posts = $this->paginate( 'Post' );

            //$this->set( compact( 'posts' ) );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                if ( isset( $this->data['Post']['new_tags'] ) )
                {
                    $new_tags = explode( ',', $this->data['Post']['new_tags'] );

                    foreach( $new_tags as $new_tag )
                    {
                        $tag['Tag']['name'] = ucfirst( strtolower( trim( $new_tag ) ) );
                        $this->Post->Tag->create();
                        if ( $this->Post->Tag->save( $tag ) )
                        {
                            $this->data['Tag']['Tag'][] = $this->Post->Tag->id;
                            //unset( $this->Post->Tag->id );
                        }

                        unset( $tag );
                    }
                }

                $this->Post->create();
                if ( $this->Post->saveAll( $this->data ) )
                {
                    $this->Session->setFlash( 'Your post has been saved.' );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }

            $tags = $this->Post->Tag->find( 'list' );
            $this->set( compact( 'tags' ) );
        }

        function admin_edit( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'That post could not be found', true ), true );
                $this->redirect( $this->referer() );
            }

            if ( !empty( $this->data ) )
            {
                if ( isset( $this->data['Post']['new_tags'] ) )
                {
                    $new_tags = explode( ',', $this->data['Post']['new_tags'] );

                    foreach( $new_tags as $new_tag )
                    {
                        $tag['Tag']['name'] = ucfirst( strtolower( trim( $new_tag ) ) );
                        $this->Post->Tag->create();
                        if ( $this->Post->Tag->save( $tag ) )
                        {
                            $this->data['Tag']['Tag'][] = $this->Post->Tag->id;
                            //unset( $this->Post->Tag->id );
                        }

                        unset( $tag );
                    }
                }

                if ( $this->Post->saveAll( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your post has been saved.', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }

                $this->Session->setFlash( __( 'Your post could not be saved.', true ) );
            }

            if ( $id && empty( $this->data ) )
            {
                $this->data = $this->Post->lock( null, $id );
                if ( $this->data === false )
                {
                    $this->Session->setFlash( __( 'The post is currently locked', true ) );
                    $this->redirect( $this->referer() );
                }
            }

            $tags = $this->Post->Tag->find( 'list' );
            $this->set( compact( 'tags' ) );
        }

        function admin_view( $slug = null )
        {
            if ( !$slug )
            {
                $this->Session->setFlash( 'That post could not be found', true );
                $this->redirect( $this->referer() );
            }

            $post = ( (int)$slug > 0 )
                ? $this->Post->read( null, $slug )
                : $this->Post->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Post.slug' => $slug
                        )
                    )
                );

            $this->set( compact( 'post' )  );
            $this->render( 'view' );
        }

        function admin_mass()
        {
            pr( $this->params['form'] );
        }
    }
?>