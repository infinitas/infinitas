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

    class CmsHelper extends AppHelper
    {
        var $helpers = array(
            //cake
            'Html', 'Form',

            // core helpers
            'Core.Wysiwyg', 'Design', 'Image'
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