<div class="three-col">
	<div class="highlight-box">
		<?php
			echo $this->Html->image(
				sprintf('/%s/img/icon.png', Inflector::underscore($pluginName)),
				array(
					'class' => 'large-icon ie6fix'
				)
			);

			echo sprintf('<h3>%s</h3>', $data['title']);
		?>
	</div>
	<p class="no-pad"><?php echo String::truncate($data['introduction'], 200, array('html' => true)); ?></p>
	<?php
		echo $this->Html->link(
			Configure::read('Website.read_more'),
			$data['link'],
			array(
				'class' => 'arrow-link',
				'title' => $data['title']
			)
		);
	?>
</div>