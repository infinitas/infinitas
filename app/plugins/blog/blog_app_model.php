<?php
    class BlogAppModel extends AppModel
    {
        var $useDbConfig = 'blog';

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

            $Folder = new Folder( CACHE.'blog' );

            $files = $Folder->read();

            if ( empty( $files[1] ) )
            {
                return true;
            }

            foreach( $files[1] as $file )
            {
                unlink( CACHE.'blog'.DS.$file );
            }

            return true;
        }
    }
?>