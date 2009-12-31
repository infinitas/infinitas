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

    class NewsletterAppModel extends AppModel
    {
        var $tablePrefix = 'newsletter_';

        function beforeSave()
        {
            parent::beforeSave();

            $this->__clearCache();
            return true;
        }

        function afterDelete()
        {
            parent::afterDelete();

            $this->__clearCache();
            return true;
        }

        private function __clearCache()
        {
            App::import( 'Folder' );

            $Folder = new Folder( CACHE.'newsletter' );

            $files = $Folder->read();

            if ( empty( $files[1] ) )
            {
                return true;
            }

            foreach( $files[1] as $file )
            {
                unlink( CACHE.'newsletter'.DS.$file );
            }

            return true;
        }
    }
?>