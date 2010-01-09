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
            'Libs.Wysiwyg', 'Libs.Design', 'Libs.Image'
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
                return $this->Html->image(
                    $this->Image->getRelativePath( 'status', 'home' ),
                    array(
                        'alt'   => __( 'Yes', true ),
                        'title' => __( 'Home page item', true ),
                        'width' => '16px'
                    )
                );
            }
            return $this->Html->image(
                $this->Image->getRelativePath( 'status', 'not-home' ),
                array(
                    'alt'   => __( 'No', true ),
                    'title' => __( 'Not on home page', true ),
                    'width' => '16px'
                )
            );
        }
    }
?>