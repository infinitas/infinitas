<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo sprintf('Infinitas Admin :: %s', $title_for_layout); ?></title>
		<?php
            echo $this->Html->meta('icon');
            echo $scripts_for_layout;

			$css_for_layout = array(
				'/assets/css/960',
				'/assets/css/admin_nav',
				'/assets/css/uncompressed/demo'
			) + $css_for_layout;

			echo $this->Html->css($css_for_layout);
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
			<?php echo $this->Infinitas->loadModules('top', true); ?>
			<div class="container_16">
				<!-- menus -->
				<div class="grid_16">
					<?php echo $this->Session->flash(); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="container_16">
				<!-- content -->
				<div class="grid_16">
					<?php echo $content_for_layout; ?>
				</div>
				<div class="clear"></div>

				<!-- footer -->
				<div class="grid_16">
					<?php
						$js_for_layout[] = '/assets/js/3rd/dock';

						echo $this->Infinitas->loadModules('bottom', true),
							 $this->Infinitas->loadModules('hidden', true),
							 $this->Html->script($js_for_layout);
					?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="powered-by">Powered By: <?php echo $this->Html->link('Infinitas', 'http://infinitas-cms.org');?></div>
		</div>
	</body>
</html>