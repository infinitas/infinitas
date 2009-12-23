<?php
    class ContentFrontpagesController extends CmsAppController
    {
        var $name = 'ContentFrontpages';

        function index()
        {
            $this->ContentFrontpage->recursive = 0;
            $this->set( 'contentFrontpages', $this->paginate() );
        }

        function admin_index()
        {
            $this->ContentFrontpage->recursive = 0;

            $this->paginate = array(
                'fields' => array(
                    'ContentFrontpage.content_id',
                    'ContentFrontpage.ordering',
                    'ContentFrontpage.created',
                    'ContentFrontpage.modified'
                ),
                'contain' => array(
                    'Content' => array(
                        'fields' => array(
                            'Content.id',
                            'Content.title',
                            'Content.active',
                        ),
                        'Category' => array(
                            'fields' => array(
                                'Category.id',
                                'Category.title'
                            )
                        )
                    )
                )
            );

            $this->set( 'contentFrontpages', $this->paginate() );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->ContentFrontpage->create();
                if ( $this->ContentFrontpage->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The content frontpage has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The content frontpage could not be saved. Please, try again.', true ) );
                }
            }

            /**
            * check what is already in the table so that the list only shows
            * what is available.
            */
            $content_ids = $this->ContentFrontpage->find(
                'list',
                array(
                    'fields' => array(
                        'ContentFrontpage.content_id',
                        'ContentFrontpage.content_id'
                    )
                )
            );

            /**
            * only get the content itmes that are not being used.
            */
            $contents = $this->ContentFrontpage->Content->find(
                'list',
                array(
                    'conditions' => array(
                        'Content.id NOT IN ( '.implode( ',', ( ( !empty( $content_ids ) ) ? $content_ids : array( 0 ) ) ).' )'
                    )
                )
            );

            if ( empty( $contents ) )
            {
                $this->Session->setFlash( __( 'You have all the items on your home page.', true ) );
                $this->redirect( $this->referer() );
            }

            $this->set( compact( 'contents' ) );
        }
    }
?>