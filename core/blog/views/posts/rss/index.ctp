<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title><?php echo Configure::read('Website.name'), ' ', __('blog'); ?></title>
		<link><?php echo $this->Html->url(array('plugin' => 'blog', 'controller' => 'posts', 'action' => 'index')); ?></link>
		<description><?php __('Blog description'); ?></description>
		<language>en-us</language>
		<pubDate><?php echo date("D, j M Y H:i:s", gmmktime()) . ' GMT';?></pubDate>
		<?php echo $this->Time->nice($time->gmt()) . ' GMT'; ?>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<generator>Infinitas Feeds</generator>
		<managingEditor>editor@example.com</managingEditor>
		<webMaster>webmaster@example.com</webMaster>
		<?php
			foreach ($posts as $post) {
				$postTime = strtotime($post['Post']['created']);

				$postLink = array(
				    'plugin' => 'posts',
				    'controller' => 'entries',
				    'action' => 'view',
				    $post['Post']['id'],
				    'year' => date('Y', $postTime),
				    'month' => date('m', $postTime),
				    'day' => date('d', $postTime),
				    $post['Post']['slug']
				);

				// You should import Sanitize
				App::import('Sanitize');
				// This is the part where we clean the body text for output as the description
				// of the rss item, this needs to have only text to make sure the feed validates
				$bodyText = preg_replace('=\(.*?\)=is', '', $post['Post']['body']);
				$bodyText = $this->Text->stripLinks($bodyText);
				$bodyText = Sanitize::stripAll($bodyText);
				$bodyText = $this->Text->truncate($bodyText, 400);
				$bodyText = str_replace('&amp;', '', $bodyText);
				$bodyText = str_replace('&nbsp;', '', $bodyText);

				?>
					<item>
						<title><?php echo $post['Post']['title']; ?></title>
						<link>http://www.example.com/articles/view/<?php echo $post['Post']['id']; ?></link>
						<description><?php echo $bodyText; ?></description>
						<?php echo $time->nice($post['Post']['created']) . ' GMT'; ?>
						<pubDate><?php echo $time->nice($time->gmt($post['Post']['created'])) . ' GMT'; ?></pubDate>
						<guid><?php echo $this->Html->link($post['Post']['title'], $postLink); ?></guid>
					</item>
				<?php
			}
		?>
	</channel>
</rss>