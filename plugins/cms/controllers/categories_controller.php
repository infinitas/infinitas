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

    class CategoriesController extends CmsAppController
    {
        var $name = 'Categories';

        var $helpers = array(
            'Filter.Filter'
        );

        function index()
        {
            $this->Category->recursive = 0;

            $categories = $this->paginate();

            // redirect if there is only one category.
            if ( count( $categories ) == 1 && Configure::read( 'Cms.auto_redirect' ) )
            {
                $this->redirect(
                    array(
                        'controller' => 'categories',
                        'action' => 'view',
                        $categories[0]['Category']['id']
                    )
                );
            }

            $this->set( 'categories', $categories );
        }

        function view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Invalid category', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }

            $category = $this->Category->read( null, $id );

            // redirect if there is only one content item.
            if ( count( $category['Content'] ) == 1 && Configure::read( 'Cms.auto_redirect' ) )
            {
                $this->redirect(
                    array(
                        'controller' => 'contents',
                        'action' => 'view',
                        $category['Content'][0]['id']
                    )
                );
            }

            $this->set( 'category', $category );
        }

        function admin_index()
        {
            $this->paginate = array(
                'order' => array(
                    'Section.title' => 'ASC',
                    'Category.ordering' => 'ASC'
                )
            );

            $this->Category->recursive = 0;
            $this->set( 'categories', $this->paginate( null, $this->Filter->filter ) );
            $this->set( 'filterOptions', $this->Filter->filterOptions );
        }

        function admin_view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Invalid category', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
            $this->set( 'category', $this->Category->read( null, $id ) );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->Category->create();
                if ( $this->Category->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The category has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The category could not be saved. Please, try again.', true ) );
                }
            }
            $sections = $this->Category->Section->find( 'list' );
            $groups = $this->Category->Group->find( 'list' );
            $this->set( compact( 'sections', 'groups' ) );
        }

        function admin_edit( $id = null )
        {
            if ( !$id && empty( $this->data ) )
            {
                $this->Session->setFlash( __( 'Invalid category', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }

            if ( !empty( $this->data ) )
            {
                if ( $this->Category->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The category has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The category could not be saved. Please, try again.', true ) );
                }
            }

            if ( empty( $this->data ) )
            {
                $this->data = $this->Category->lock( null, $id );
                if ( $this->data === false )
                {
                    $this->Session->setFlash( __( 'The category is currently locked', true ) );
                    $this->redirect( $this->referer() );
                }
            }

            $sections = $this->Category->Section->find( 'list' );
            $groups = $this->Category->Group->find( 'list' );
            $this->set( compact( 'sections', 'groups' ) );
        }

        function admin_delete( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( 'That Category could not be found', true );
                $this->redirect( $this->referer() );
            }

            $category = $this->Category->read( null, $id );

            if ( !empty( $category['Content'] ) )
            {
                $this->Session->setFlash( __( 'There are still content itmes in this category. Remove them and try again.', true ) );
                $this->redirect( $this->referer() );
            }

            return parent::admin_delete( $id );
        }
    }
?>