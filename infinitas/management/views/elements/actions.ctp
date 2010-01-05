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
?>
<?php echo $this->Html->link( __( 'Blog', true ), array( 'plugin' => 'blog' ), array( 'class' => 'link' ) ); ?>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Pending Posts', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'index', 'Post.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Create a New Post', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'add' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending Comments', true ), array( 'plugin' => 'management', 'controller' => 'comments', 'action' => 'index', 'Comment.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Accept All Comments', true ), array( 'plugin' => 'blog', 'controller' => 'comments', 'action' => 'acceptAll' ), array( 'title' => __( 'Mark all comments as accepted', true ) ) ); ?></li>
</ul>

<?php echo $this->Html->link( __( 'Newsletters', true ), array( 'plugin' => 'newsletters' ), array( 'class' => 'link' ) ); ?>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Pending Newsletters', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'Newsletter.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Sending in Progress', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'sending' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New Newsletter', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'add' ) ); ?></li>
</ul>

<?php echo $this->Html->link( __( 'Cms', true ), array( 'plugin' => 'cms' ), array( 'class' => 'link' ) ); ?>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Pending Content', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'index', 'Content.active' => 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New Content Page', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'add' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Home Page', true ), array( 'plugin' => 'cms', 'controller' => 'contentFrontpages', 'action' => 'index' ) ); ?></li>
</ul>

<?php echo $this->Html->link( __( 'Cart', true ), array( 'plugin' => 'cart' ), array( 'class' => 'link' ) ); ?>

<?php echo $this->Html->link( __( 'Global Config', true ), array( 'plugin' => 'management', 'controller' => 'configs' ), array( 'class' => 'link' ) ); ?>
<?php echo $this->Html->link( __( 'File Manager', true ), array( 'plugin' => 'management', 'controller' => 'file_manager' ), array( 'class' => 'link' ) ); ?>