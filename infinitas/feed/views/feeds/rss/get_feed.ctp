<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title><?php echo Configure::read('Website.name'), ' ', $raw['Feed']['name']; ?></title>
		<link><?php echo $this->Html->url(array('plugin' => $raw['Feed']['plugin'], 'controller' => $raw['Feed']['controller'], 'action' => 'index')); ?></link>
		<description><?php echo strip_tags($raw['Feed']['description']); ?></description>
		<language>en-us</language>
		<pubDate><?php echo date("D, j M Y H:i:s", gmmktime()) . ' GMT';?></pubDate>
		<?php echo $this->Time->nice($this->Time->gmt()) . ' GMT'; ?>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<generator>Infinitas Feeds</generator>
		<copyright>&copy; <?php echo Configure::read('Website.name').' '.date('Y'); ?></copyright>
		<managingEditor><?php echo Configure::read('Feed.editor_email'); ?></managingEditor>
		<webMaster><?php echo Configure::read('Website.email'); ?></webMaster>
		<?php
			foreach ($feed as $item) {
				if(isset($item['Feed']['name'])){
					$post['title'] = $item['Feed']['name'];
				}
				else if(isset($item['Feed']['title'])){
					$post['title'] = $item['Feed']['title'];
				}

				if(isset($item['Feed']['body'])){
					$post['body'] = $item['Feed']['body'];
				}
				else if(isset($item['Feed']['info'])){
					$post['info'] = $item['Feed']['info'];
				}
				else if(isset($item['Feed']['description'])){
					$post['description'] = $item['Feed']['description'];
				}

				$post['time'] = isset($item['Feed']['date']) ? strtotime($post['Feed']['date']) : strtotime('now');

				$eventData = $this->Event->trigger($item['Feed']['plugin'].'.slugUrl', $item['Feed']);
				$post['url'] = $this->Html->url(current($eventData['onSlugUrl']));

				App::import('Sanitize');
				// make sure the feed only has text
				$post['body'] = preg_replace('=\(.*?\)=is', '', $post['body']);
				$post['body'] = $this->Text->stripLinks($post['body']);
				$post['body'] = Sanitize::stripAll($post['body']);
				$truncate = Configure::read('Feed.truncate');
				$post['body'] = $this->Text->truncate($post['body'], !empty($truncate) ? $truncate : 400);
				$post['body'] = str_replace('&amp;', '', $post['body']);
				$post['body'] = str_replace('&nbsp;', '', $post['body']);

				?>
					<item>
						<title><?php echo $post['title']; ?></title>
						<link><?php echo $post['url']; ?></link>
						<description><?php echo $post['body']; ?></description>
						<?php echo $this->Time->nice($post['time']) . ' GMT'; ?>
						<pubDate><?php echo $time->nice($time->gmt($post['time'])) . ' GMT'; ?></pubDate>
						<guid><?php echo $this->Html->link($post['name'], current($eventData['onSlugUrl'])); ?></guid>
					</item>
				<?php
			}
		?>
	</channel>
</rss>