<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet/less" type="text/css" href="/assets/less/infinitas/admin.less">
		<?php
			echo $this->Html->charset();
			echo $this->Html->tag('title', sprintf('Infinitas Admin :: %s', $title_for_layout));
            echo $this->Html->meta('icon');

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

				$icon = $this->Html->tag('i', '', array('class' => 'icon-%s'));
				echo $this->Html->tag('div', implode('', array(
					$this->Html->link(sprintf($icon, 'flag'), 'http://infinitas.lighthouseapp.com/projects/43419-infinitas/tickets/new', array(
						'escape' => false,
						'class' => 'btn btn-large',
						'title' => 'Lighthouse issues',
						'target' => '_blank'
					)),
					$this->Html->link(sprintf($icon, 'github'), 'http://github.com/infinitas/infinitas', array(
						'escape' => false,
						'class' => 'btn btn-large',
						'title' => 'GitHub',
						'target' => '_blank'
					)),
					$this->Html->link(sprintf($icon, 'twitter'), 'http://twitter.com/infinit8s', array(
						'escape' => false,
						'class' => 'btn btn-large',
						'title' => 'GitHub',
						'target' => '_blank'
					))
				)), array('class' => 'btn-group'));
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