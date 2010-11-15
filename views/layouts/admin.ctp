<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo sprintf('Infinitas Admin :: %s', $title_for_layout); ?></title>
		<?php
            echo $this->Html->meta('icon');
            echo $scripts_for_layout;
			$css = array(
				'/assets/css/960gs/960',
				'/assets/css/admin_nav',
				'/assets/css/960gs/uncompressed/demo',
				'/assets/css/3rd/date',
			);
			
			echo $this->Html->css(array_merge($css, $css_for_layout));
		?>
		<script type="text/javascript">
			Infinitas = <?php echo json_encode(isset($infinitasJsData) ? $infinitasJsData : ''); ?>;
			if (Infinitas.base != '/') {
				Infinitas.base = Infinitas.base + '/';
			}
		</script>
	</head>
	<body>
		<div id="wrap">
			<?php echo $this->ModuleLoader->load('top', true); ?>
			<div class="container_16">
				<!-- menus -->
				<div class="grid_16">
					<?php echo $this->Session->flash(); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="container_16 <?php echo $class_name_for_layout; ?>">
				<!-- content -->
				<?php echo $content_for_layout; ?>
				<div class="clear"></div>

				<!-- footer -->
				<div class="grid_16">
					<?php
						echo $this->ModuleLoader->load('bottom', true),
							 $this->ModuleLoader->load('hidden', true),
							 $this->Html->script($js_for_layout);
					?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="powered-by">Powered By: <?php echo $this->Html->link('Infinitas', 'http://infinitas-cms.org');?></div>
		</div>
	</body>
</html>