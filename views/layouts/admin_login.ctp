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
            echo $this->Html->meta( 'icon' );
            echo $this->Html->css( 'admin_login' );
    		echo $scripts_for_layout;
        ?>
	</head>
	<body>
		<div id="wrap">
			<div id="header">Infinitas Cms</div>
			<?php echo $this->Design->niceBox(); ?>
			<h1><?php __('Welcome back'); ?></h1>
			<p>
				<?php echo __('Please login to continue to the admin area', true); ?>
			</p>
			<?php
				echo $content_for_layout;

				echo $this->Html->link(
					__('Take me back', true),
					'/',
					array(
						'class' => 'back'
					)
				);
			?>
			<?php echo $this->Design->niceBoxEnd(); ?>
			<div id="footer">
				<p class="copyright">
			        <?php //TODO: Fix this error: echo $this->element( 'admin/bottom' ); ?>
				</p>
			</div>
		</div>
	</body>
</html>