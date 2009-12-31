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

            $contentFrontpages = $this->paginate();

            if ( empty( $contentFrontpages ) )
            {
                $this->Session->setFlash( __( 'Nothing to see here.', true ) );
                $this->redirect( $this->referer() );
            }
            $this->set( 'contentFrontpages', $contentFrontpages );
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