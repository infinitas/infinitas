<?php
    class CategoriesController extends CmsAppController
    {
        var $name = 'Categories';

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
            $this->Category->recursive = 0;
            $this->set( 'categories', $this->paginate( null, $this->Filter->filter ) );
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