<?php
    /**
    * if there is no database file redirect any url to the installer.s
    */
    if ( !file_exists( APP.'config'.DS.'database.php' ) )
    {
        Router::connect( '/', array( 'plugin' => 'installer', 'controller' => 'install', 'action' => 'index' ) );
    }

    /**
     * Here, we are connecting '/' (base path) to controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, /app/views/pages/home.ctp)...
     */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));

    /**
     * ...and connect the rest of 'Pages' controller's urls.
     */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));





    /**
     * management routes
     */
    Router::connect('/admin', array( 'plugin' => 'management', 'controller' => 'management', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true ) );
    Router::connect('/admin/management', array( 'plugin' => 'management', 'controller' => 'management', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true ) );

    /**
     * blog routes
     */
    Router::connect('/admin/blog', array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true ) );
    Router::connect('/blog',       array( 'plugin' => 'blog', 'controller' => 'posts' ) );

    /**
     * newsletter routes
     */
    Router::connect('/admin/newsletter', array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true ) );
    Router::connect('/newsletter',       array( 'plugin' => 'newsletter', 'controller' => 'newsletters' ) );

    /**
     * cms routes
     */
    Router::connect('/admin/cms', array( 'plugin' => 'cms', 'controller' => 'sections', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true ) );
    Router::connect('/cms',       array( 'plugin' => 'cms', 'controller' => 'contentFrontpages' ) );
?>