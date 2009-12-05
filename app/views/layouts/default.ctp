<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<?php echo $this->Html->charset(); ?>
    	<title>
    		<?php
    		    echo __( 'PhpDev :: ', true ), $title_for_layout;
        	?>
    	</title>
        <?php
            echo $this->Html->meta('icon');
            echo $html->css( 'default' );
    		echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <!-- terrafirma1.0 by nodethirtythree design http://www.nodethirtythree.com -->
        <div id="outer">
        	<div id="upbg"></div>
        	<div id="inner">
        		<div id="header">
        			<h1><span>Php</span>dev<sup>1.0</sup></h1>
        			<h2>by dogmatic69</h2>
        		</div>
        		<div id="splash"></div>
                <?php
                    echo $this->element( 'menu/top', array( 'plugin' => 'core' ) );
                    echo $this->element( 'main_content', array( 'content_for_layout' => $content_for_layout ) );
                    echo $this->element( 'right_bar' );
                ?>
        		<div id="footer">
        			&copy; Php-Dev.co.za. All rights reserved. <?php echo $this->Html->link( 'Hosted by Change Me', 'http://www.changeme.com' ); ?>
        			<?php
                        echo $this->Html->link(
        					$this->Html->image(
            					'cake.power.gif',
            					array(
                					'alt'=> __( 'CakePHP: the rapid development php framework', true),
                					'border' => '0'
                				)
                			),
        					'http://www.cakephp.org/',
        					array(
            					'target' => '_blank',
            					'escape' => false,
            					'style' => 'float:right;'
            				)
        				);
        			?>
        		</div>
        	</div>
        </div>
    	<?php echo $cakeDebug; ?>
    </body>
</html>