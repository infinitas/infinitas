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
    }

    /**
     * index
     *
     * @return void
     */
    function index()
    {
        $this->pageTitle = __( 'Installation: Welcome', true );

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

    /**
     * database setup
     *
     * @return void
     */
    function database()
    {
        $this->pageTitle = __( 'Step 1: Database', true );
        if ( !empty( $this->data ) )
        {
            // test database connection
            if ( mysql_connect( $this->data['Install']['host'], $this->data['Install']['login'], $this->data['Install']['password'] ) &&
                    mysql_select_db( $this->data['Install']['database'] ) )
            {
                // rename database.php.install
                rename( APP . 'config' . DS . 'database.php.install', APP . 'config' . DS . 'database.php' );
                // open database.php file
                App::import( 'Core', 'File' );
                $file = new File( APP . 'config' . DS . 'database.php', true );
                $content = $file->read();
                // write database.php file
                $content = str_replace( '{default_host}', $this->data['Install']['host'], $content );
                $content = str_replace( '{default_login}', $this->data['Install']['login'], $content );
                $content = str_replace( '{default_password}', $this->data['Install']['password'], $content );
                $content = str_replace( '{default_database}', $this->data['Install']['database'], $content );
                if ( $file->write( $content ) )
                {
                    $this->redirect( array( 'action' => 'data' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'Could not write database.php file.', true ) );
                }
            }
            else
            {
                $this->Session->setFlash( __( 'Could not connect to database.', true ) );
            }
        }
    }

    /**
     * configuration
     *
     * @return void
     */
    function config()
    {
        $this->pageTitle = __( 'Step 2: Run SQL', true );
        // App::import('Core', 'Model');
        // $Model = new Model;
        if ( isset( $this->params['named']['run'] ) )
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
                $this->__executeSQLScript( $db, CONFIGS . 'sql' . DS . 'croogo.sql' );
                $this->__executeSQLScript( $db, CONFIGS . 'sql' . DS . 'croogo_data.sql' );

                $this->redirect( array( 'action' => 'finish' ) );
            }
        }
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

        if ( isset( $this->params['named']['delete'] ) )
        {
            App::import( 'Core', 'Folder' );
            $this->folder = new Folder;
            if ( $this->folder->delete( APP . 'plugins' . DS . 'install' ) )
            {
                $this->Session->setFlash( __( 'Installataion files deleted successfully.', true ) );
                $this->redirect( '/' );
            }
            else
            {
                $this->Session->setFlash( __( 'Could not delete installation files.', true ) );
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
        $statements = explode( ';', $statements );

        foreach ( $statements as $statement )
        {
            if ( trim( $statement ) != '' )
            {
                $db->query( $statement );
            }
        }
    }
}

?>