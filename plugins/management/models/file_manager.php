<?php
    /**
     *
     *
     */
    class FileManager extends ManagementAppModel
    {
        var $name = 'FileManager';

        var $useTable = false;

        var $ignore = array(
            '.htaccess',
            '.htpasswd',
            '.gitignore'
        );

        function beforeFind( $queryData )
        {
            $this->basePath = Configure::read( 'FileManager.base_path' );

            if ( empty( $this->basePath ) )
            {
                $this->validationErrors[] = array(
                    'field' => 'basePath',
                    'message' => __( 'Base path does not exist', true )
                );
                return false;
            }

            if ( isset( $queryData['conditions']['path'] ) )
            {
                $this->path = $this->basePath.$queryData['conditions']['path'];
            }

            App::import( 'Folder' );
            App::import( 'File' );
            $this->Folder = new Folder( $this->path );

            if ( empty( $this->Folder->path ) )
            {
                $this->validationErrors[] = array(
                    'field' => 'path',
                    'message' => __( 'Path does not exist', true )
                );
                return false;
            }

            $this->fileList = $this->Folder->read();

            return true;
        }

        function find( $findType = 'all', $conditions = array() )
        {
            if ( ! $this->beforeFind( $conditions ) )
            {
                return false;
            }
            return $this->__read( $findType, $conditions );
        }

        function __read( $findType, $conditions )
        {
            foreach( $conditions['types'] as $type )
            {
                switch( Inflector::pluralize( $type ) )
                {
                    case 'files':
                        switch( $findType )
                        {
                            case 'all':
                                $return['files'] = $this->__advancedFileFind( $conditions );
                                break;

                            case 'list':
                                $return['files'] =  $this->__simpleFileFind( $conditions );
                                break;
                        } // switch
                        break;

                    case 'folders':
                        switch( $findType )
                        {
                            case 'all':
                                $return['folders'] = $this->__advancedFolderFind( $conditions );
                                break;

                            case 'list':
                                $return['folders'] =  $this->__simpleFolderFind( $conditions );
                                break;
                        } // switch
                        break;
                } // switch
            }

            pr( $return );
            exit;
        }

        function __advancedFileFind( $conditions )
        {
            if ( empty( $this->fileList[1] ) )
            {
                return array();
            }
            $i = 0;

            foreach( $this->fileList[1] as $file )
            {
                if ( in_array( $file, $this->ignore ) )
                {
                    continue;
                }

                if ( $this->recursive > -2 )
                {
                    $return[$i]['File']['path']      = $this->Folder->path.DS.$file;
                    $return[$i]['File']['relative']  = $this->__relativePath( $this->Folder->path.DS.$file );

                    if ( $this->recursive > -1 )
                    {
                        $this->File = new File( $return[$i]['File']['path'] );
                        $info                            = $this->File->info();
                        $return[$i]['File']['dirname']   = $info['dirname'];
                        $return[$i]['File']['basename']  = $info['basename'];
                        $return[$i]['File']['extension'] = $info['extension'];
                        $return[$i]['File']['filename']  = $info['filename'];

                        if ( $this->recursive > 0 )
                        {
                            $return[$i]['File']['size']      = $this->File->size( $return[$i]['File']['path'] );
                            $return[$i]['File']['owner']     = $this->File->owner();
                            $return[$i]['File']['gorup']     = $this->File->group();
                            $return[$i]['File']['accessed']  = $this->File->lastAccess();
                            $return[$i]['File']['modidfied'] = $this->File->lastChange( $return[$i]['File']['path'] );
                            $return[$i]['File']['charmod']   = $this->File->perms( $return[$i]['File']['path'] );

                            if ( $this->recursive > 1 )
                            {
                                $return[$i]['File']['type']      = filetype( $return[$i]['File']['path'] );
                                $return[$i]['File']['md5']  = $this->File->md5();
                                $return[$i]['File']['stat'] = stat( $return[$i]['File']['path'] );

                                $i++;
                            }
                            $i++;
                        }

                        $i++;
                    }

                    $i++;
                }

                $i++;
            }
            return $return;
        }

        function __simpleFileFind( $conditions )
        {
            if ( empty( $this->fileList[1] ) )
            {
                return array();
            }

            foreach( $this->fileList[1] as $file )
            {
                if ( in_array( $file, $this->ignore ) )
                {
                    continue;
                }

                $return[] = $this->Folder->path.DS.$file;
            }
            return $return;
        }

        function __advancedFolderFind( $conditions )
        {
            pr( $this->fileList[0] );
        }

        function __simpleFolderFind( $conditions )
        {
            if ( empty( $this->fileList[0] ) )
            {
                return array();
            }

            foreach( $this->fileList[0] as $file )
            {
                if ( in_array( $file, $this->ignore ) )
                {
                    continue;
                }

                $return[] = $this->Folder->path.DS.$file;
            }
            return $return;
        }

        function __relativePath( $path )
        {
            return 'todo';
        }
    }
?>