<?php
    /**
     * Blog action view element file.
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
     * @package       blog
     * @subpackage    blog.views.elements.actions
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<?php echo $this->Html->link( __( 'Dashboard', true ), '/admin/blog', array( 'class' => 'link' ) ); ?>
<h3><?php __( 'Posts' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Active', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'index', 'Post.active' => 1 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'index', 'Post.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'add' ) ); ?></li>
</ul>
<h3><?php __( 'Comments' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Index', true ), array( 'plugin' => 'blog', 'controller' => 'comments', 'action' => 'index' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending', true ), array( 'plugin' => 'blog', 'controller' => 'comments', 'action' => 'index', 'Post.active' => 0 ) ); ?></li>
</ul>
<h3><?php __( 'Maintanence' ); ?></h3>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Accept All Comments', true ), array( 'plugin' => 'blog', 'controller' => 'comments', 'action' => 'acceptAll' ), array( 'title' => __( 'Mark all comments as accepted', true ) ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Perge Old Comments', true ), array( 'plugin' => 'blog', 'controller' => 'comments', 'action' => 'commentPurge', 'Blog.Post' ), array( 'title' => __( 'Mark all comments as accepted', true ) ) ); ?></li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Posts', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'blog',
                    'm' => 'post'
                ),
                array(
                    'title' => __( 'Save a backup of all posts', true )
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Comments', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'blog',
                    'm' => 'comment'
                ),
                array(
                    'title' => __( 'Save a backup of all comments', true )
                )
            );
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link(
                __( 'Backup Tags', true ),
                array(
                    'plugin' => 'core',
                    'controller' => 'backups',
                    'action' => 'backup',
                    'p' => 'blog',
                    'm' => 'tag'
                ),
                array(
                    'title' => __( 'Save a backup of all tags', true )
                )
            );
        ?>
    </li>
</ul>