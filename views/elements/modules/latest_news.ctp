<?php
	$feeds = Cache::read('global_feeds');
	if (!$feeds) {
		$feeds = ClassRegistry::init('Blog.Post')->find(
			'feed',
			array(
				'setup' => array(
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => 'view',
				),
				'fields' => array(
					'Post.id',
					'Post.title',
					'Post.intro AS body',
					'Post.created AS date'
				),
				'feed' => array(
					'Core.Comment' => array(
						'setup' => array(
							'plugin' => 'comment',
							'controller' => 'comments',
							'action' => 'view',
						),
						'fields' => array(
							'Comment.id',
							'Comment.name',
							'Comment.comment',
							'Comment.created'
						)
					)
				),
				'order' => array(
					'date' => 'DESC'
				),
				'limit' => 10
			)
		);
	}

	foreach($feeds as $feed){
		?>
			<h3><?php echo $feed['Feed']['title'] ?></h3>
			<p class="news">
				<?php
					echo strip_tags( html_entity_decode($feed['Feed']['body']) );
					echo $this->Html->link(
						__(Configure::read('Website.read_more'), true),
						array(
							'plugin' => $feed['Feed']['plugin'],
							'controller' => $feed['Feed']['controller'],
							'action' => $feed['Feed']['action'],
							$feed['Feed']['id'],
						),
						array(
							'class' => 'more'
						)
					);
				?>
			</p>
		<?
	}
?>