<?php echo $this->Html->link( __( 'Dashboard', true ), array( 'plugin' => 'management' ), array( 'class' => 'link' ) ); ?>
<?php echo $this->Html->link( __( 'Blog', true ), array( 'plugin' => 'blog' ), array( 'class' => 'link' ) ); ?>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Pending Posts', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'index', 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Create a New Post', true ), array( 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'add' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Pending Comments', true ), array( 'plugin' => 'blog', 'controller' => 'comments', 'action' => 'index', 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Accept All Comments', true ), array( 'plugin' => 'blog', 'controller' => 'comments', 'action' => 'acceptAll' ), array( 'title' => __( 'Mark all comments as accepted', true ) ) ); ?></li>
</ul>

<?php echo $this->Html->link( __( 'Newsletters', true ), array( 'plugin' => 'newsletters' ), array( 'class' => 'link' ) ); ?>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Pending Newsletters', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Sending in Progress', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index', 'sending' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New Newsletter', true ), array( 'plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'add' ) ); ?></li>
</ul>

<?php echo $this->Html->link( __( 'Cms', true ), array( 'plugin' => 'cms' ), array( 'class' => 'link' ) ); ?>
<ul class="nav">
    <li><?php echo $this->Html->link( __( 'Pending Content', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'index', 0 ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'New Content Page', true ), array( 'plugin' => 'cms', 'controller' => 'contents', 'action' => 'add' ) ); ?></li>
    <li><?php echo $this->Html->link( __( 'Home Page', true ), array( 'plugin' => 'cms', 'controller' => 'contentFrontpages', 'action' => 'index' ) ); ?></li>
</ul>

<?php echo $this->Html->link( __( 'Cart', true ), array( 'plugin' => 'cart' ), array( 'class' => 'link' ) ); ?>

<?php echo $this->Html->link( __( 'Global Config', true ), array( 'plugin' => 'management', 'controller' => 'configs', 'action' => 'index' ), array( 'class' => 'link' ) ); ?>