<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet/less" type="text/css" href="/assets/less/infinitas/admin.less">
		<?php
			echo $this->Html->charset();
			echo $this->Html->tag('title', sprintf('Infinitas Admin :: %s', $title_for_layout));
            echo $this->Html->meta('icon');
			echo $this->Html->css(array(
				'Assets.bootstrap'
			));

			echo $this->Html->scriptBlock("Infinitas = {};\nif (Infinitas.base != '/') {Infinitas.base = Infinitas.base + '/';}\n");
		?>
	</head>
	<body id="error">
		<div class="container">
			<?php
				echo $this->Session->flash();
				echo $this->Html->tag('div', implode('', array(
					$this->Html->tag('h1', $this->Html->image('Assets.infinitas/small.png') . __d('infinitas', 'Infinitas')),
					$this->Html->tag('hr'),
					$content_for_layout
				)), array('class' => 'content'));
			?>
		</div>
		<?php
			echo $this->element('Assets.admin_footer');
			echo $this->Html->script(array(
				'Assets.3rd/less'
			))
		?>
	</body>
</html>