<?php
class InstallController extends InstallerAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    var $name = 'Install';

    /**
     * No models required
     *
     * @var array
     * @access public
     */
    var $uses = array();

    /**
     * No components required
     *
     * @var array
     * @access public
     */
    var $components = null;

    var $phpVersion = '5.0';

    /**
     * beforeFilter
     *
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'installer';


        App::import( 'Component', 'Session' );
        $this->Session = new SessionComponent;

        $this->sql = array(
            'core_tables'      => APP.'plugins'.DS.'installer'.DS.'config'.DS.'schema'.DS.'infinitas.sql',
            'core_data'        => APP.'plugins'.DS.'installer'.DS.'config'.DS.'schema'.DS.'infinitas_core_data.sql',
            'core_sample_data' => APP.'plugins'.DS.'installer'.DS.'config'.DS.'schema'.DS.'infinitas_sample_data.sql',
        );
    }

    /**
     * index
     *
     * @return void
     */
    function index()
    {
        $this->set( 'title_for_layout', __( 'Installation: Welcome', true ) );

        // core setup
        $setup[] = array (
            'label' => __( 'PHP version', true ).' >= '.$this->phpVersion.'.x',
            'value' => phpversion() >= $this->phpVersion ? 'Yes' : 'No',
            'desc'  => 'Php '.$this->phpVersion.'.x is recomended, although php 4.x may run Infinitas fine.'
        );

        $setup[] = array (
            'label' => __( 'zlib compression support', true ),
            'value' => extension_loaded( 'zlib' ) ? 'Yes' : 'No',
            'desc'  => 'zlib is required for some of the functionality in Infinitas'
        );

        $setup[] = array (
            'label' => __( 'MySQL support', true ),
            'value' => ( function_exists( 'mysql_connect' ) ) ? 'Yes' : 'No',
            'desc'  => 'Infinitas uses mysql for generating dynamic content. Other databases will follow soon.'
        );

        // path status
        $paths[] = array (
            'path'      => APP.'config',
            'writeable' => is_writable( APP.'config' ) ? 'Yes' : 'No',
            'desc'      => 'This path needs to be writeable for Infinitas to complete the installation.'
        );

        $paths[] = array (
            'path'      => APP.'tmp',
            'writeable' => is_writable( APP.'tmp' ) ? 'Yes' : 'No',
            'desc'      => 'The tmp dir needs to be writable for caching to work in Infinitas.'
        );

        $paths[] = array (
            'path'      => APP.'webroot',
            'writeable' => is_writable( APP.'webroot' ) ? 'Yes' : 'No',
            'desc'      => 'This needs to be web accesible or your images and css will not be found.'
        );

        // recomendations
        $recomendations = array (
            array (
            	'setting'       => 'safe_mode',
            	'recomendation' => 'Off',
                'desc'          => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0'
            ),
            array (
            	'setting'       => 'display_errors',
            	'recomendation' => 'On',
            	'desc'          => 'Infinitas will handle errors througout the app'
            ),
            array (
            	'setting'       => 'file_uploads',
            	'recomendation' => 'On',
            	'desc'          => 'File uploads are needed for the wysiwyg editors and system installers'
            ),
            array (
            	'setting'       => 'magic_quotes_runtime',
            	'recomendation' => 'Off',
            	'desc'          => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
            ),
            array (
            	'setting'       => 'register_globals',
            	'recomendation' => 'Off',
            	'desc'          => 'This feature has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
            ),
            array (
            	'setting'       => 'output_buffering',
            	'recomendation' => 'Off',
            	'desc'          => 'Infinitas will handle output_buffering for you throughout the app'
            ),
            array (
            	'setting'       => 'session.auto_start',
            	'recomendation' => 'Off',
            	'desc'          => 'Sessions are completly hanled by Infinitas'
            ),
        );

        foreach( $recomendations as $k => $setting )
        {
            $recomendations[$k]['actual'] = ( (int)ini_get( $setting['setting'] ) ? 'On' : 'Off' );
            $recomendations[$k]['state']  = ( $recomendations[$k]['actual'] == $setting['setting'] ) ? 'No' : 'Yes';
        }

        $this->set( compact( 'setup', 'paths', 'recomendations' ) );
    }

    function licence()
    {
        $this->set( 'title_for_layout', __( 'Licence', true ) );
    }

    function __testConnection()
    {
        return
            mysql_connect(
                $this->data['Install']['host'],
                $this->data['Install']['login'],
                $this->data['Install']['password']
            )

            &&

            mysql_select_db(
                $this->data['Install']['database']
            );
    }

    /**
     * database setup
     *
     * @return void
     */
    function database()
    {
        $this->set( 'title_for_layout', __( 'Database Configuration', true ) );
        if ( !empty( $this->data ) )
        {
            if ( $this->__testConnection() )
            {
                rename( APP.'config'.DS.'db.install', APP.'config'.DS.'database.php' );

                App::import( 'Core', 'File' );
                $file = new File( APP.'config'.DS.'database.php', true );
                $content = $file->read();

                $content = str_replace( '{default_host}', $this->data['Install']['host'], $content );
                $content = str_replace( '{default_login}', $this->data['Install']['login'], $content );
                $content = str_replace( '{default_password}', $this->data['Install']['password'], $content );
                $content = str_replace( '{default_database}', $this->data['Install']['database'], $content );

                if ( $file->write( $content ) )
                {
                    $this->Session->setFlash( __( 'Database configuration saved.', true ) );
                    $this->redirect( array( 'action' => 'install' ) );
                }
                $this->Session->setFlash( __( 'Could not write database.php file.', true ) );
            }
            else
            {
                $this->Session->setFlash( __( 'That connection does not seem to be valid', true ) );
            }
        }
    }

    /**
     * configuration
     *
     * @return void
     */
    function install()
    {
        $this->set( 'title_for_layout', __( 'Install Database', true ) );

        $files = true;
        if ( empty( $this->sql ) )
        {
            $files = false;
        }

        foreach( $this->sql as $type => $path )
        {
            if ( !is_file( $path ) )
            {
                $files = false;
            }
        }

        if ( !empty( $this->data ) && $files )
        {
            App::import( 'Core', 'File' );
            App::import( 'Model', 'ConnectionManager' );

            $db = ConnectionManager::getDataSource( 'default' );

            if ( !$db->isConnected() )
            {
                $this->Session->setFlash( __( 'Could not connect to database.', true ) );
            }
            else
            {
                if ( $this->__executeSQLScript( $db, $this->sql['core_tables'] ) )
                {
                    $this->__executeSQLScript( $db, $this->sql['core_data'] );

                    $this->Session->setFlash( __( 'Database Tables installed', true ) );

                    if ( $this->data['Install']['sample_data'] )
                    {
                        $this->Session->setFlash( __( 'Database Tables installed with sample data.', true ) );
                        $this->__executeSQLScript( $db, $this->sql['core_sample_data'] );
                    }

                    foreach( $this->sql as $name => $path )
                    {
                        if ( !strstr( $name, 'core' ) )
                        {
                            $this->Session->setFlash( __( 'Database Tables installed with sample data and some other data.', true ) );
                            $this->__executeSQLScript( $db, $path );
                        }
                    }

                    $this->redirect( array( 'action' => 'siteConfig' ) );
                }

                $this->Session->setFlash( __( 'There was an error installing database data.', true ) );
            }
        }

        if ( !$files )
        {
            $this->Session->setFlash( __( 'There is a problem with the sql installation files.', true ) );
        }
    }

    function siteConfig()
    {
        $this->set( 'title_for_layout', __( 'Site Configuration', true ) );
    }

    /**
     * done
     *
     * Remind the user to delete 'install' plugin.
     *
     * @return void
     */
    function done()
    {
        $this->pageTitle = __( 'Installation completed successfully', true );

        if ( isset( $this->params['named']['rename'] ) )
        {
            if ( is_dir( APP.'plugins'.DS.'installer' ) && rename( APP.'plugins'.DS.'installer', APP.'plugins'.DS.'installer'.time() ) )
            {
                $this->Session->setFlash( __( 'The instilation folder has been renamed, if you ever need to run installation again just rename it back to installer.', true ) );
            }

            else
            {
                $this->Session->setFlash( __( 'Could not find the installer directory.', true ) );
            }
        }
    }

    /**
     * Execute SQL file
     *
     * @link http://cakebaker.42dh.com/2007/04/16/writing-an-installer-for-your-cakephp-application/
     * @param object $db Database
     * @param string $fileName sql file
     * @return void
     */
    function __executeSQLScript( $db, $fileName )
    {
        $statements = file_get_contents( $fileName );
        $statements = explode( ';'."\r\n", $statements );

        $status = true;
        foreach ( $statements as $statement )
        {
            if ( trim( $statement ) != '' )
            {
                $status = $status && $db->query( $statement );
            }
        }

        return $status;
    }
}

?>