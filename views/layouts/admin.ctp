<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<?php echo $this->Html->charset(); ?>
    	<title>
    		<?php
    		    echo __( 'Infinitas :: ', true ), $title_for_layout;
        	?>
    	</title>
        <?php
            echo $this->Html->meta( 'icon' );
            echo $this->Html->css( 'admin' );
            echo $this->Html->css( 'admin.pagination' );
    		echo $scripts_for_layout;
    		echo $javascript->link( 'fckeditor' );
        ?>
    </head>
    <body>
        <div id="main">
        	<div id="header">
                <div class="left"></div>
                <div class="right"></div>
                <?php echo $this->Session->flash(); ?>
        		<ul id="top-navigation">
        			<li class="<?php echo ( ( $this->here == '/admin' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Dashboard', true ), '/admin' ); ?></span></span></li>
        			<li class="<?php echo ( ( $this->plugin == 'blog' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Blog', true ), '/admin/blog' ); ?></span></span></li>
        			<li class="<?php echo ( ( $this->plugin == 'newsletter' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Newsletters', true ), '/admin/newsletter' ); ?></span></span></li>
        			<li class="<?php echo ( ( $this->plugin == 'cms' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Cms', true ), '/admin/cms' ); ?></span></span></li>
        			<li class="<?php echo ( ( $this->plugin == 'forum' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Forum', true ), '/admin/forum' ); ?></span></span></li>
        			<li class="<?php echo ( ( $this->plugin == 'cart' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Cart', true ), '/admin/cart' ); ?></span></span></li>
        			<li class="<?php echo ( ( $this->plugin == 'accounts' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Account', true ), '/admin/account' ); ?></span></span></li>

                    <li class="<?php echo ( ( $this->plugin == 'core' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Developer', true ), '/admin/core' ); ?></span></span></li>
                    <li class="<?php echo ( ( $this->plugin == 'management' && $this->here != '/admin' ) ? 'active' : '' ); ?>"><span><span><?php echo $this->Html->link( __( 'Admin', true ), '/admin/management' ); ?></span></span></li>
        		</ul>
        	</div>
        	<div id="middle">
                <div id="wrap">
            		<div id="left-column">
                        <?php echo $this->element( 'actions' ); ?>
            		</div>
            		<div id="center-column">
                        <?php echo $content_for_layout; ?>
                    </div>
                    <?php echo $this->Design->niceBox( 'right-column', $this->element( 'right_boxes' ) ); ?>
                </div>
                <div class="clr"></div>
            </div>
        	<div id="footer">
                <div class="left"></div>
                <div class="right"></div>
            </div>
        </div>
        <?php echo $this->element( 'admin/bottom' ); ?>
    </body>
</html>