<?php
    /**
     *
     *
     */
    class CmsHelper extends AppHelper
    {
        var $helpers = array(
            //cake
            'Html',

            // core helpers
            'Core.Wysiwyg'
        );

        function homePageItem( $record = array(), $model = 'Content' )
        {
            if ( empty( $record ) )
            {
                $this->errors[] = 'cant check nothing.';
                return false;
            }

            if ( !empty( $record['ContentFrontpage'] ) )
            {
                return $this->Html->link(
                    $this->Html->image(
                        'core/icons/actions/16/home-yes.png'
                    ),
                    array(
                        'action' => 'removeHomePage',
                        $record[$model]['id'],
                    ),
                    array(
                        'title' => __( 'Remove from the home page', true ),
                        'alt' => __( 'Remove from home page', true ),
                        'escape' => false
                    )
                );
            }

            else
            {
                return $this->Html->link(
                    $this->Html->image(
                        'core/icons/actions/16/home-no.png'
                    ),
                    array(
                        'action' => 'addHomePage',
                        $record[$model]['id'],
                    ),
                    array(
                        'title' => __( 'Add to the home page', true ),
                        'alt' => __( 'Add to the home page', true ),
                        'escape' => false
                    )
                );
            }
        }
    }
?>