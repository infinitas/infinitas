<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo sprintf('Infinitas Admin :: %s', $title_for_layout); ?></title>
		<?php
            echo $this->Html->meta('icon');
			echo $this->Compress->css($css_for_layout);
		?>
		<link rel="stylesheet/less" type="text/css" href="/assets/less/infinitas/admin.less">
		<script type="text/javascript">
			Infinitas = <?php echo json_encode(isset($infinitasJsData) ? $infinitasJsData : ''); ?>;
			if (Infinitas.base != '/') {
				Infinitas.base = Infinitas.base + '/';
			}
		</script>
	</head>
	<body>
		<?php
			echo $this->ModuleLoader->load('top', true);
			echo $this->Session->flash();
		?>
		<div id="wrap">
			<div class="container">
				<div class="grid_16">
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
						echo $this->ModuleLoader->load('bottom', true);
					?>
				</div>
				<div class="clear"></div>
			</div>
			<div id="push"></div>
		</div>
		<div id="footer">
			<div class="container">
				<p class="credit">Powered By: <?php echo $this->Html->link('Infinitas', 'http://infinitas-cms.org');?></p>
			</div>
		</div>
		<?php
			echo $this->ModuleLoader->load('hidden', true),
				$this->Compress->script($js_for_layout),
				$this->fetch('scripts_for_layout');
		?>
	</body>
</html>