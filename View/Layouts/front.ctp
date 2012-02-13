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

            echo $this->Html->css(array('front') + $css_for_layout);
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
					<div class="<?php echo isset($this->request->params['plugin'])?$this->request->params['plugin']:''; ?>">
						<div class="<?php echo isset($this->request->params['controller'])?$this->request->params['controller']:''; ?>">
							<div class="<?php echo isset($this->request->params['action'])?$this->request->params['action']:''; ?>">
								<div id="content">
									<?php
										echo $this->Session->flash(), $content_for_layout;
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
                					'alt'=> __( 'CakePHP: the rapid development php framework'),
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