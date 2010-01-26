<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
    echo $this->Html->link( __( 'Dashboard', true ), array( 'plugin' => 'management' ), array( 'class' => 'link' ) );

	echo $this->Html->link( __( 'Blog', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'dashboard' ), array( 'class' => 'link' ) );
	echo $this->Html->link( __( 'Newsletters', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard' ), array( 'class' => 'link' ) );
	echo $this->Html->link( __( 'Cms', true ), array( 'plugin' => 'cms', 'controller' => 'categories', 'action' => 'dashboard' ), array( 'class' => 'link' ) );
	//echo $this->Html->link( __( 'Cart', true ), array( 'plugin' => 'cart' ), array( 'class' => 'link' ) );
	echo '</br>';
	echo $this->Html->link( __( 'Config Manager', true ), array( 'plugin' => 'management', 'controller' => 'configs' ), array( 'class' => 'link' ) );
	echo $this->Html->link( __( 'File Manager', true ), array( 'plugin' => 'filemanager', 'controller' => 'file_manager' ), array( 'class' => 'link' ) );
	echo $this->Html->link( __( 'Routing Manager', true ), array( 'plugin' => 'management', 'controller' => 'routes' ), array( 'class' => 'link' ) );
	echo $this->Html->link( __( 'Themes Manager', true ), array( 'plugin' => 'management', 'controller' => 'themes' ), array( 'class' => 'link' ) );
	echo $this->Html->link( __( 'Modules Manager', true ), array( 'plugin' => 'management', 'controller' => 'modules' ), array( 'class' => 'link' ) );
?>