<?php
    /**
     * Cms action view element file.
     *
     * this is the file that shows on the top left of the admin interface, it has
     * the sub links that are available in the plugin.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       cms
     * @subpackage    cms.views.elements.actions
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<?php echo $this->Html->link( __( 'Dashboard', true ), '/admin/cms', array( 'class' => 'link' ) ); ?>
<h3><?php __( 'Categories' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'All', true ), array( 'plugin' => 'cms', 'controller' => 'categories', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Active', true ), array( 'plugin' => 'cms', 'controller' => 'categories', 'action' => 'index', 'Category.active' => 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'cms', 'controller' => 'categories', 'action' => 'index', 'Category.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'cms', 'controller' => 'categories', 'action' => 'add' ) ); ?></li>
</ul>
<h3><?php __( 'Contents' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Layouts', true ), array( 'plugin' => 'cms', 'controller' => 'layouts', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'All', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Active', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'index', 'Content.active' => 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'index', 'Content.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'add' ) ); ?></li>
</ul>
<h3><?php __( 'Front Pages' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'All', true ), array( 'plugin' => 'cms', 'controller' => 'frontpages', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'cms', 'controller' => 'frontpages', 'action' => 'add' ) ); ?></li>
</ul>
<h3><?php __( 'Featured Pages' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'All', true ), array( 'plugin' => 'cms', 'controller' => 'features', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'cms', 'controller' => 'features', 'action' => 'add' ) ); ?></li>
</ul>
<h3><?php __( 'Comments' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'management', 'controller' => 'comments', 'action' => 'index', 'Comment.class' => 'Cms' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'management', 'controller' => 'comments', 'action' => 'index', 'Comment.class' => 'Cms', 'Comment.active' => 0 ) ); ?></li>
</ul>
<h3><?php __( 'Maintanence' ); ?></h3>
<ul class="nav">
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Sections', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'cms',
                    'm' => 'section'
                ),
                array(
                    'title' => __( 'Save a backup of all sections', true )
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Categories', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'cms',
                    'm' => 'categories'
                ),
                array(
                    'title' => __( 'Save a backup of all categories', true )
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Contents', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'cms',
                    'm' => 'contents'
                ),
                array(
                    'title' => __( 'Save a backup of all contents', true )
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Front Page', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'cms',
                    'm' => 'frontPage'
                ),
                array(
                    'title' => __( 'Save a backup of all front pages', true )
                )
            );
        ?>
    </li>
</ul>