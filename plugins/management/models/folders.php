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

            $this->fileList = $Folder->read();
            unset( $this->fileList[1] );

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

            $data = Cache::read( 'folders_'.sha1( $this->path.$conditions ) );
            if ( $data !== false )
            {
                //return $data;
            }

            return $this->__read( $findType, $conditions );
        }

        function chmod( $path )
        {

        }

        function __read( $findType, $conditions )
        {
            switch( $findType )
            {
                case 'all':
                    $this->__advancedFolderFind( $conditions );
                    break;

                case 'list':
                    $this->__simpleFolderFind( $conditions );
                    break;
            } // switch

            Cache::write( 'folders_'.sha1( $this->path.$conditions ), $this->return );

            return $this->return;
        }

        function __advancedFolderFind( $conditions )
        {
            if ( empty( $this->fileList[0] ) )
            {
                $this->return = array();
                return true;
            }
            $i = 0;

            foreach( $this->fileList[0] as $folder )
            {
                if ( in_array( $folder, $this->ignore ) )
                {
                    continue;
                }

                if ( $this->recursive > -2 )
                {
                    $Folder = & new Folder( $this->path.DS.$folder );

                    $this->return[$i]['Folder']['path']      = $Folder->path;
                    $this->return[$i]['Folder']['relative']  = $this->__relativePath( $Folder->path );

                    if ( $this->recursive > -1 )
                    {


                        if ( $this->recursive > 0 )
                        {
                            $this->return[$i]['Folder']['size']   = $Folder->dirsize();

                            if ( $this->recursive > 1 )
                            {
                                $this->return[$i]['Folder']['realpath'] = $Folder->realpath( $Folder->path );
                                $this->return[$i]['Folder']['absolute'] = $Folder->isAbsolute( $Folder->path );
                                $this->return[$i]['Folder']['windows']  = $Folder->isWindowsPath( $Folder->path );
                                $this->return[$i]['Folder']['Children'] = $Folder->tree( $Folder->path );
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
            if ( empty( $this->fileList[0] ) )
            {
                $this->return = array();
                return true;
            }

            foreach( $this->fileList[0] as $file )
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
                        sort( $this->fileList[0] );
                    }
                    else
                    {
                        rsort( $this->fileList[0] );
                    }
                }
            }
        }
    }
?>