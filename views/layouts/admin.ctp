<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<?php echo $this->Html->charset(); ?>
    	<title>
    		<?php
    		    echo __( 'Infinitas Admin :: ', true ), $title_for_layout;
        	?>
    	</title>
        <?php
            echo $this->Html->meta('icon');
            echo $scripts_for_layout;
            echo $this->Html->css($css_for_layout);
        ?>
		<script type="text/javascript">
			Infinitas = <?php echo json_encode(isset($infinitasJsData) ? $infinitasJsData : ''); ?>;
			if (Infinitas.base != '/') {
				Infinitas.base = Infinitas.base + '/';
			}
		</script>
		<?php
            echo $this->Html->script($js_for_layout);
        ?>
    </head>
    <body>
        <div id="wrap">
        	<div id="header">
                <?php
					if(isset($this->Infinitas)) {
						echo $this->Infinitas->loadModules('top', true);
					}
				?>
        	</div>
			<?php
				if(isset($this->Session)) {
					echo $this->Session->flash();
				}
			?>
        	<div id="content">
				<div class="<?php echo isset($this->params['plugin'])?$this->params['plugin']:''; ?>">
					<div class="<?php echo isset($this->params['controller'])?$this->params['controller']:''; ?>">
						<div class="<?php echo isset($this->params['action'])?$this->params['action']:''; ?>">
							<?php echo $content_for_layout; ?>
						</div>
					</div>
				</div>
			</div>
        	<div id="footer">
				<?php
					if(isset($this->Infinitas)) {
						echo $this->Infinitas->loadModules('bottom', true);
					}
				 ?>
            </div>
        </div>
        <?php
        	echo $this->element('admin/bottom');
        ?>
    </body>
</html>