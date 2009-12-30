<?php
    class ConfigsController extends ManagementAppController
    {
        var $name = 'Configs';

        var $helpers = array(
            'Text'
        );

        function admin_index()
        {
            $configs = $this->paginate( 'Config' );

            $this->set( compact( 'configs' ) );
        }

        function admin_edit( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'That config could not be found', true ), true );
                $this->redirect( $this->referer() );
            }

            if ( !empty( $this->data ) )
            {
                if ( $this->Config->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your config has been saved.', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }

                $this->Session->setFlash( __( 'Your config could not be saved.', true ) );
            }

            if ( $id && empty( $this->data ) )
            {
                $this->data = $this->Config->read( null, $id );
            }
        }
    }
?>