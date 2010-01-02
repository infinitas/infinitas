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
                    $this->return[$i]['Folder']['name']      = basename( $this->return[$i]['Folder']['path'] );
                    $this->return[$i]['Folder']['parent']    = dirname( $this->return[$i]['Folder']['path'] );
                    $this->return[$i]['Folder']['relative']  = $this->__relativePath( $this->return[$i]['Folder']['path'] );

                    $stat                                      = stat( $this->return[$i]['Folder']['path'] );

                    $this->__fileStatus( $i, $stat );

                    if ( $this->recursive > -1 )
                    {
                        $this->return[$i]['Folder']['accessed']  = date( 'Y-m-d H:i:s', $stat['atime'] );
                        $this->return[$i]['Folder']['modified']  = date( 'Y-m-d H:i:s', $stat['atime'] );
                        $this->return[$i]['Folder']['created']   = date( 'Y-m-d H:i:s', $stat['atime'] );

                        if ( $this->recursive > 0 )
                        {
                            $this->return[$i]['Folder']['size']        = $Folder->dirsize();
                            $this->return[$i]['Folder']['absolute']    = $Folder->isAbsolute( $this->return[$i]['Folder']['path'] );
                            $children                                  = $Folder->tree( $this->return[$i]['Folder']['path'] );
                            $this->return[$i]['Folder']['sub_folders'] = count( $children[0] ) -1;
                            $this->return[$i]['Folder']['sub_files']   = count( $children[1] );


                            if ( $this->recursive > 1 )
                            {
                                $this->return[$i]['Folder']['realpath'] = $Folder->realpath( $this->return[$i]['Folder']['path'] );
                                $this->return[$i]['Folder']['windows']  = $Folder->isWindowsPath( $this->return[$i]['Folder']['path'] );
                                $this->return[$i]['Folder']['Children'] = $children;
                                $this->return[$i]['Folder']['Extended'] = $stat;
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

        function __fileStatus( $currentI, $stat )
        {
            $ts=array(
                0140000=>'ssocket', 0120000=>'llink', 0100000=>'-file',
                0060000=>'bblock', 0040000=>'ddir', 0020000=>'cchar',
                0010000=>'pfifo'
            );
            $p = $stat['mode'];
            $t = decoct( $stat['mode'] & 0170000 );

            $str =(array_key_exists(octdec($t),$ts))?$ts[octdec($t)]{0}:'u';
            $str.=(($p&0x0100)?'r':'-').(($p&0x0080)?'w':'-');
            $str.=(($p&0x0040)?(($p&0x0800)?'s':'x'):(($p&0x0800)?'S':'-'));
            $str.=(($p&0x0020)?'r':'-').(($p&0x0010)?'w':'-');
            $str.=(($p&0x0008)?(($p&0x0400)?'s':'x'):(($p&0x0400)?'S':'-'));
            $str.=(($p&0x0004)?'r':'-').(($p&0x0002)?'w':'-');
            $str.=(($p&0x0001)?(($p&0x0200)?'t':'x'):(($p&0x0200)?'T':'-'));

            $this->return[$currentI]['Folder']['permission'] = $str;
            $this->return[$currentI]['Folder']['octal']      = sprintf("0%o", 0777 & $p);
            $this->return[$currentI]['Folder']['owner']      = $stat['uid'];
            $this->return[$currentI]['Folder']['group']      = $stat['gid'];
            $this->return[$currentI]['Folder']['owner_name'] = ( function_exists( 'posix_getpwuid' ) ) ? posix_getpwuid( $this->return[$currentI]['Folder']['owner'] ) : '';
            $this->return[$currentI]['Folder']['group_name'] = ( function_exists( 'posix_getgrgid' ) ) ? posix_getgrgid( $this->return[$currentI]['Folder']['group'] ) : '';
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