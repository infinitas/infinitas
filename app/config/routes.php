<?php
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
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