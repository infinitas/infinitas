<?php
    /**
     *
     *
     */
    class FeaturesController extends CmsAppController
    {
        var $name = 'Features';

        /**
         * Helpers.
         *
         * @access public
         * @var array
         */
        var $helpers = array( 'Filter.Filter' );

        function index()
        {
            $this->Feature->recursive = 0;
            $this->set( 'features', $this->paginate );
        }

        function admin_index()
        {
            $this->Feature->recursive = 0;

            $this->paginate = array(
                'fields' => array(
                    'Feature.id',
                    'Feature.content_id',
                    'Feature.ordering',
                    'Feature.created',
                    'Feature.modified'
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

            $features = $this->paginate();

            $this->set( compact( 'features' ) );
            $this->set( 'filterOptions', $this->Filter->filterOptions );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->Feature->create();
                if ( $this->Feature->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The featured content has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The featured content could not be saved. Please, try again.', true ) );
                }
            }

            /**
             * check what is already in the table so that the list only shows
             * what is available.
             */
            $content_ids = $this->Feature->find(
                'list',
                array(
                    'fields' => array(
                        'Feature.content_id',
                        'Feature.content_id'
                    )
                )
            );

            /**
             * only get the content itmes that are not being used.
             */
            $contents = $this->Feature->Content->find(
                'list',
                array(
                    'conditions' => array(
                        'Content.id NOT IN ( '.implode( ',', ( ( !empty( $content_ids ) ) ? $content_ids : array( 0 ) ) ).' )'
                    )
                )
            );

            if ( empty( $contents ) )
            {
                $this->Session->setFlash( __( 'You have already made all the content items featured.', true ) );
                $this->redirect( $this->referer() );
            }

            $this->set( compact( 'contents' ) );
        }
    }
?>