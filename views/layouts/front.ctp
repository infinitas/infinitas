<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<?php echo $this->Html->charset(); ?>
    	<title>
    		<?php
    		    echo Configure::read('Website.name'), ' :: ', $title_for_layout;
        	?>
    	</title>
        <?php
            echo $this->Html->meta('icon');

            echo $html->css(array('front') + $css_for_layout);
    		echo $this->Html->script($js_for_layout);
    		echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <!-- terrafirma1.0 by nodethirtythree design http://www.nodethirtythree.com -->
        <div id="outer">
        	<div id="upbg"></div>
        	<div id="inner">
        		<div id="header">
        			<h1><?php echo $this->Html->link(Configure::read('Website.name'), '/'); ?><sup>1.0</sup></h1>
        		</div>
        		<div id="splash"></div>
				<?php
					echo $this->ModuleLoader->load('top');
                    echo $this->Session->flash();
                ?>
				<div id="primarycontent">
					<div class="<?php echo isset($this->params['plugin'])?$this->params['plugin']:''; ?>">
						<div class="<?php echo isset($this->params['controller'])?$this->params['controller']:''; ?>">
							<div class="<?php echo isset($this->params['action'])?$this->params['action']:''; ?>">
								<div id="content">
									<?php
										echo $session->flash(), $content_for_layout;
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="secondarycontent">
					<?php echo $this->ModuleLoader->load('right'); ?>
				</div>
        		<div id="footer">
        			<?php 
						echo $this->Html->link( 'Infinitas CMS', 'http://infinitas-cms.org' );

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
    </body>
</html>