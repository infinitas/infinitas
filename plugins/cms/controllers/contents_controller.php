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

    class ContentsController extends CmsAppController
    {
        var $name = 'Contents';

        function index()
        {
            $this->Content->recursive = 0;
            $this->set( 'contents', $this->paginate() );
        }

        function view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Invalid content', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
            $this->set( 'content', $this->Content->read( null, $id ) );
        }

        function admin_index()
        {
            $this->paginate = array(
                'order' => array(
                    'Category.title' => 'ASC',
                    'Content.ordering' => 'ASC'
                )
            );

            $this->Content->recursive = 1;
            $this->set( 'contents', $this->paginate( null, $this->Filter->filter ) );
        }

        function admin_view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Invalid content', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
            $this->set( 'content', $this->Content->read( null, $id ) );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->Content->create();
                if ( $this->Content->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The content has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The content could not be saved. Please, try again.', true ) );
                }
            }
            $categories = $this->Content->Category->find( 'list' );
            $this->set( compact( 'categories' ) );
        }

        function admin_edit( $id = null )
        {
            if ( !$id && empty( $this->data ) )
            {
                $this->Session->setFlash( __( 'Invalid content', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
            if ( !empty( $this->data ) )
            {
                if ( $this->Content->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The content has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The content could not be saved. Please, try again.', true ) );
                }
            }
            if ( empty( $this->data ) )
            {
                $this->data = $this->Content->lock( null, $id );
                if ( $this->data === false )
                {
                    $this->Session->setFlash( __( 'The content item is currently locked', true ) );
                    $this->redirect( $this->referer() );
                }
            }
            $categories = $this->Content->Category->find( 'list' );
            $this->set( compact( 'categories' ) );
        }

        function admin_toggle( $id = null )
        {

        }
    }
?>