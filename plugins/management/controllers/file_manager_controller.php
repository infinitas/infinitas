<?php
    /**
     *
     *
     */
    class FileManagerController extends ManagementAppController
    {
        var $name = 'FileManager';

        var $uses = array( 'Management.FileManager' );

        function admin_index( $path = null )
        {
            $this->FileManager->recursive = 2;
            $data = $this->FileManager->find(
                'all',
                array(
                    'types' => array(
                        'files',
                        'folders'
                    ),
                    'fields' => array(
                    ),
                    'conditions' => array(
                        'path' => '/'
                    ),
                    'order' => array(
                        'name' => 'ASC'
                    )
                )
            );

            $this->set( compact( 'data' ) );
        }

        function admin_view( $file = null )
        {
            if ( !$file )
            {
                $this->Session->setFlash( __( 'Please select a file first', true ) );
                $this->redirect( $this->referer() );
            }

            // @todo mediaViews
        }

        function admin_download( $file = null )
        {
            if ( !$file )
            {
                $this->Session->setFlash( __( 'Please select a file first', true ) );
                $this->redirect( $this->referer() );
            }

            // @todo mediaViews
        }

        function admin_delete( $file = null )
        {
            if ( !$file )
            {
                $this->Session->setFlash( __( 'Please select a file first', true ) );
                $this->redirect( $this->referer() );
            }

            if ( $this->FileManager->delete( $file ) )
            {
                $this->Session->setFlash( __( 'File deleted', true ) );
                $this->redirect( $this->referer() );
            }

            $this->Session->setFlash( __( 'File could not be deleted', true ) );
            $this->redirect( $this->referer() );
        }
    }
?>