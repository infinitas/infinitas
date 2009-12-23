<?php
    class TemplatesController extends NewsletterAppController
    {
        var $name = 'Templates';

        var $helpers = array(
        );

        private $sampleText = '<p>This is some sample text to test your template</p>';

        function beforeFilter()
        {
            parent::beforeFilter();
        }

        function admin_index()
        {
            $this->paginate = array(
                'fields' => array(
                    'Template.id',
                    'Template.name',
                    'Template.locked',
                    'Template.locked_by',
                    'Template.locked_since',
                    'Template.created',
                    'Template.modified',
                )
            );

            $templates = $this->paginate( 'Template' );

            $this->set( compact( 'templates' ) );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->Template->create();
                if ( $this->Template->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your template has been saved.', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }
        }

        function admin_edit( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Please select a template', true ) );
                $this->redirect( $this->referer() );
            }

            if ( !empty( $this->data ) )
            {
                $this->Template->create();
                if ( $this->Template->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your template has been saved.', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }

            if ( $id && empty( $this->data ) )
            {
                $this->Template->recursive = -1;
                $this->data = $this->Template->lock( null, $id );
                if ( $this->data === false )
                {
                    $this->Session->setFlash( __( 'The template is currently locked', true ) );
                    $this->redirect( $this->referer() );
                }
            }
        }

        function admin_view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Please select a template', true ) );
                $this->redirect( $this->referer() );
            }

            $this->set( 'template', $this->Template->read( null, $id ) );
        }

        function admin_preview( $id = null )
        {
            $this->layout = 'ajax';

            if ( !$id )
            {
                $this->set( 'data', __( 'The template was not found', true ) );
            }
            else
            {
                $template = $this->Template->read( array( 'header', 'footer' ), $id );
                $this->set( 'data', $template['Template']['header'].$this->sampleText.$template['Template']['footer'] );
            }

        }
    }
?>