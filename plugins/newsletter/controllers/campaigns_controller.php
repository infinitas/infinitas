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

    class CampaignsController extends NewsletterAppController
    {
        var $name = 'Campaigns';

        /**
         * Helpers.
         *
         * @access public
         * @var array
         */
        var $helpers = array(
            'Filter.Filter'
        );

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
            $this->set( 'filterOptions', $this->Filter->filterOptions );
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

        /** stop this being called */
        function admin_delete( $id = null )
        {
            return false;
        }

        protected function admin_mass( )
        {
            $model = $this->modelNames[0];
            $ids    = $this->__massGetIds( $this->data[$model] );

            switch( $this->__massGetAction( $this->params['form'] ) )
            {
                case 'delete':
                        return parent::__massActionDelete( $this->__canDelete( $ids ) );
                    break;
            } // switch
        }

        private function __canDelete( $ids )
        {
            $newsletters = $this->Campaign->Newsletter->find(
                'list',
                array(
                    'fields' => array(
                        'Newsletter.campaign_id',
                        'Newsletter.campaign_id'
                    ),
                    'conditions' => array(
                        'Newsletter.sent' => 1,
                        'Newsletter.campaign_id' => $ids
                    )
                )
            );

            if ( empty( $newsletters ) )
            {
                return $ids;
            }

            foreach( $ids as $k => $v )
            {
                if ( isset( $newsletters[$v] ) )
                {
                    unset( $ids[$k] );
                }
            }

            if ( !empty( $ids ) )
            {
                return $ids;
            }

            $this->Session->setFlash( __( 'None of the campaigns you selected are deletable.', true ) );
            $this->redirect( $this->referer() );

        }
    }
?>