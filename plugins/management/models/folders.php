<?php
    /**
     *
     *
     */
    class Folders extends ManagementAppModel
    {
        var $name = 'Folders';

        var $useTable = false;

        var $hasMany = array(
            'Management.Files'
        );

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

            $this->path = $this->basePath;

            if ( isset( $queryData['conditions']['path'] ) )
            {
                $this->path = $this->basePath.$queryData['conditions']['path'];
            }

            App::import( 'Folder' );
            App::import( 'File' );
            $Folder = new Folder( $this->path );

            if ( empty( $Folder->path ) )
            {
                $this->validationErrors[] = array(
                    'field' => 'path',
                    'message' => __( 'Path does not exist', true )
                );
                return false;
            }

            $FileList = $Folder->read();

            if ( !empty( $queryData['order'] ) )
            {
                $this->__order( $queryData['order'] );
            }

            return true;
        }

        function find( $findType = 'all', $conditions = array() )
        {
            if ( ! $this->beforeFind( $conditions ) )
            {
                return false;
            }

            $data = Cache::read( sha1( $this->path.$conditions ) );
            if ( $data !== false )
            {
                pr( $data );
                exit;
                return $data;
            }

            return $this->__read( $findType, $conditions );
        }

        function chmod( $path )
        {

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
                                $this->__advancedFileFind( $conditions );
                                break;

                            case 'list':
                                $this->__simpleFileFind( $conditions );
                                break;
                        } // switch
                        break;

                    case 'folders':
                        switch( $findType )
                        {
                            case 'all':
                                $this->__advancedFolderFind( $conditions );
                                break;

                            case 'list':
                                $this->__simpleFolderFind( $conditions );
                                break;
                        } // switch
                        break;
                } // switch

                $data[$type][$findType] = $this->return;
                $this->return = null;
            }

            Cache::write( sha1( $this->path.$conditions ), $data );

            pr( $data );
            exit;
        }

        function __advancedFileFind( $conditions )
        {
            if ( empty( $FileList[1] ) )
            {
                $this->return = array();
                return true;
            }
            $i = 0;

            foreach( $FileList[1] as $file )
            {
                if ( in_array( $file, $this->ignore ) )
                {
                    continue;
                }

                if ( $this->recursive > -2 )
                {
                    $this->return[$i]['File']['path']      = $Folder->path.DS.$file;
                    $this->return[$i]['File']['relative']  = $this->__relativePath( $Folder->path.DS.$file );

                    if ( $this->recursive > -1 )
                    {
                        $File = new File( $this->return[$i]['File']['path'] );
                        $info                            = $File->info();
                        $this->return[$i]['File']['dirname']   = $info['dirname'];
                        $this->return[$i]['File']['basename']  = $info['basename'];
                        $this->return[$i]['File']['extension'] = $info['extension'];
                        $this->return[$i]['File']['filename']  = $info['filename'];
                        $this->return[$i]['File']['writable']  = $File->writable();

                        if ( $this->recursive > 0 )
                        {
                            $this->return[$i]['File']['size']      = $File->size();
                            $this->return[$i]['File']['owner']     = $File->owner();
                            $this->return[$i]['File']['gorup']     = $File->group();
                            $this->return[$i]['File']['accessed']  = $File->lastAccess();
                            $this->return[$i]['File']['modidfied'] = $File->lastChange();
                            $this->return[$i]['File']['charmod']   = $File->perms();

                            if ( $this->recursive > 1 )
                            {
                                $this->return[$i]['File']['type']     = filetype( $this->return[$i]['File']['path'] );
                                $this->return[$i]['File']['md5']      = $File->md5();
                                $this->return[$i]['File']['Extended'] = stat( $this->return[$i]['File']['path'] );

                                $i++;
                                continue;
                            }
                            $i++;
                        }

                        $i++;
                    }

                    $i++;
                }

                $i++;
            }
            return true;
        }

        function __simpleFileFind( $conditions )
        {
            if ( empty( $FileList[1] ) )
            {
                $this->return = array();
                return true;
            }

            foreach( $FileList[1] as $file )
            {
                if ( in_array( $file, $this->ignore ) )
                {
                    continue;
                }

                $this->return[] = $Folder->path.DS.$file;
            }
            return true;
        }

        function __advancedFolderFind( $conditions )
        {
            if ( empty( $FileList[0] ) )
            {
                $this->return = array();
                return true;
            }
            $i = 0;

            foreach( $FileList[0] as $folder )
            {
                if ( in_array( $folder, $this->ignore ) )
                {
                    continue;
                }

                if ( $this->recursive > -2 )
                {
                    $Folder = & new Folder( $Folder->path.DS.$folder );

                    $this->return[$i]['Folder']['path']      = $Folder->path;
                    $this->return[$i]['Folder']['relative']  = $this->__relativePath( $Folder->path );

                    if ( $this->recursive > -1 )
                    {


                        if ( $this->recursive > 0 )
                        {
                            $this->return[$i]['Folder']['size']   = $Folder->dirsize();

                            if ( $this->recursive > 1 )
                            {
                                $this->return[$i]['realpath'] = $Folder->realpath( $Folder->path );
                                $this->return[$i]['absolute'] = $Folder->isAbsolute( $Folder->path );
                                $this->return[$i]['windows']  = $Folder->isWindowsPath( $Folder->path );
                                $this->return[$i]['Children'] = $Folder->tree( $Folder->path );
                                $i++;
                                continue;
                            }
                            $i++;
                        }

                        $i++;
                    }

                    $i++;
                }

                $i++;
            }
            return true;
        }

        function __simpleFolderFind( $conditions )
        {
            if ( empty( $FileList[0] ) )
            {
                $this->return = array();
                return true;
            }

            foreach( $FileList[0] as $file )
            {
                if ( in_array( $file, $this->ignore ) )
                {
                    continue;
                }

                $this->return[] = $Folder->path.DS.$file;
            }
            return true;
        }

        function __relativePath( $path )
        {
            return 'todo';
        }

        function __order( $order = array( 'name' => 'ASC' ) )
        {
            if ( !is_array( $order ) )
            {
                $order = array( $order );
            }

            foreach( $order as $field => $direction )
            {
                if ( $field == 'name' )
                {
                    if ( strtolower( $direction ) == 'asc' )
                    {
                        sort( $FileList[0] );
                        sort( $FileList[1] );
                    }
                    else
                    {
                        rsort( $FileList[0] );
                        rsort( $FileList[1] );
                    }
                }
            }
        }
    }
?>