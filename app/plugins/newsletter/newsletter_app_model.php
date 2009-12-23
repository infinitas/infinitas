<?php
    /**
     *
     *
     */
    class NewsletterAppModel extends AppModel
    {
        var $useDbConfig = 'newsletter';

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