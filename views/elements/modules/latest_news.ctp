<?php
	$feeds = Cache::read('global_feeds');
	if (empty($feeds)) {
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
					'Post.slug',
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
							'Comment.name AS slug',
							'Comment.comment',
							'Comment.created'
						),
						'conditions' => array(
							'Comment.active' => 1
						)
					),
					'Cms.Content' => array(
						'setup' => array(
							'plugin' => 'cms',
							'controller' => 'contents',
							'action' => 'view',
						),
						'fields' => array(
							'Content.id',
							'Content.title',
							'Content.slug',
							'Content.body',
							'Content.created'
						),
						'conditions' => array(
							'Post.active' => 1
						)
					)
				),
				'conditions' => array(
					'Post.active' => 1
				),
				'order' => array(
					'date' => 'DESC'
				),
				'limit' => 10
			)
		);
	}

	if (empty($feeds)) {
		echo __('No news is good news.', true);
	}
	else{
		Cache::write('global_feeds', $feeds);
		foreach($feeds as $feed){
			?>
				<h3><?php echo $feed['Feed']['title'] ?></h3>
				<p class="news">
					<?php
						$more = $this->Html->link(
							__(Configure::read('Website.read_more'), true),
							array(
								'plugin' => $feed['Feed']['plugin'],
								'controller' => $feed['Feed']['controller'],
								'action' => $feed['Feed']['action'],
								'id' => $feed['Feed']['id'],
								'category' => 'news-feed',
								'slug' => $feed['Feed']['slug']
							),
							array(
								'class' => 'more'
							)
						);

						echo $this->Text->truncate($feed['Feed']['body'], 150, array('html' => true)), ' ', $more;
					?>
				</p>
			<?php
		}
	}
?>