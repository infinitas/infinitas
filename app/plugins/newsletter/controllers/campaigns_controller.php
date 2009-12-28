<?php
    /**
     *
     *
     */
    class CampaignsController extends NewsletterAppController
    {
        var $name = 'Campaigns';

        function admin_index()
        {
            $this->paginate = array(
                'fields' => array(
                    'Campaign.id',
                    'Campaign.name',
                    'Campaign.description',
                    'Campaign.newsletter_count',
                    'Campaign.active',
                    'Campaign.locked',
                    'Campaign.locked_by',
                    'Campaign.locked_since',
                    'Campaign.created',
                    'Campaign.modified'
                ),
                'Campaign' => array(
                    'contain' => array(
                        'Template' => array(
                            'fields' => array(
                                'Template.id',
                                'Template.name'
                            )
                        ),
                        'Newsletter' => array(
                            'fields' => array(
                                'Newsletter.sent'
                            )
                        ),
                        'Locker' => array(
                            'fields' => array(
                                'Locker.id',
                                'Locker.username'
                            )
                        )
                    )
                )
            );

            $campaigns = $this->paginate( 'Campaign', $this->Filter->filter );

            $this->set( compact( 'campaigns' ) );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->Campaign->create();
                if ( $this->Campaign->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your campaign has been saved.', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }

            $templates = $this->Campaign->Template->find( 'list' );
            $newsletters = $this->Campaign->Newsletter->find( 'list' );
            $this->set( compact( 'templates', 'newsletters' ) );
        }

        function admin_edit( $id )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Please select a campaign', true ) );
                $this->redirect( $this->referer() );
            }

            if ( !empty( $this->data ) )
            {
                $data = $this->Campaign->find(
                    'first',
                    array(
                        'fields' => array(
                            'Campaign.id',
                            'Campaign.active',
                        ),
                        'conditions' => array(
                            'Campaign.id' => $this->data['Campaign']['id']
                        ),
                        'contain' => array(
                            'Newsletter' => array(
                                'fields' => array(
                                    'Newsletter.id'
                                )
                            )
                        )
                    )
                );

                $message = '';

                if ( !$data['Campaign']['active'] && empty( $data['Newsletter'] ) )
                {
                    $this->data['Campaign']['active'] = 0;
                    $message = __( 'The campaign was de-activated because it has no mails.', true );

                }

                if ( $this->Campaign->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your campaign has been saved. '.$message, true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }

            if ( $id && empty( $this->data ) )
            {
                $this->data = $this->Campaign->lock( null, $id );
                if ( $this->data === false )
                {
                    $this->Session->setFlash( __( 'The campaign is currently locked', true ) );
                    $this->redirect( $this->referer() );
                }
            }

            $templates = $this->Campaign->Template->find( 'list' );
            $newsletters = $this->Campaign->Newsletter->find( 'list' );
            $this->set( compact( 'templates', 'newsletters' ) );
        }

        function admin_toggle( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Please select a campaign', true ) );
                $this->redirect( $this->referer() );
            }

            $data = $this->Campaign->find(
                'first',
                array(
                    'fields' => array(
                        'Campaign.id',
                        'Campaign.active',
                    ),
                    'conditions' => array(
                        'Campaign.id' => $id
                    ),
                    'contain' => array(
                        'Newsletter' => array(
                            'fields' => array(
                                'Newsletter.id'
                            )
                        )
                    )
                )
            );

            if ( !$data['Campaign']['active'] && empty( $data['Newsletter'] ) )
            {
                $this->Session->setFlash( __( 'You can not enable a campaign with no mails.', true ) );
                $this->redirect( $this->referer() );
            }

            return parent::admin_toggle( $id );
        }

        function admin_delete( $id = null )
        {
            return parent::admin_delete( $id );
        }
    }
?>