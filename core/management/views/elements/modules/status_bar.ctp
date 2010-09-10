<div id="side_bar">
	<ul>
		<li alt="Home"><?php echo $this->Html->image('status_bar/home.png', array('url' => '/')); ?>Home</li>
	</ul>
	<span class="jx-separator-left"></span>
	<ul>
		<li alt="Profile"><?php echo $this->Html->image('status_bar/profile.png', array('url' => '/me')); ?></li>
		<li alt="About"><?php echo $this->Html->image('status_bar/information.png', array('url' => '/about')); ?></li>
		<li alt="Contact"><?php echo $this->Html->image('status_bar/megaphone.png', array('url' => array('plugin' => 'contact', 'controller' => 'branches'))); ?></li>
	</ul>
	<span class="jx-separator-left"></span>
	<ul class="jx-bar-button-left">
		<?php $shortUrl = ClassRegistry::init('ShortUrls.ShortUrl')->newUrl($this->here, true, $this->webroot); ?>
		<li alt="Share with the world" class="addthis_toolbox addthis_default_style">
			<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c114e15110816c8" class="addthis_button_compact">Share</a>
			<span class="addthis_separator">|</span>
			<a class="addthis_button_facebook"></a>
			<a class="addthis_button_myspace"></a>
			<a class="addthis_button_google"></a>
			<a class="addthis_button_twitter"></a>
			<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c114e15110816c8"></script>
		</li>
	</ul>
	<span class="jx-separator-left"></span>
	<div></div>
	<ul class="jx-bar-button-right">
		<li alt="Feed"><?php echo $this->Html->image('status_bar/feed.png', array('url' => str_replace('/thinkmoney', '', $this->here).'.rss')); ?></li>
	</ul>
	<span class="jx-separator-right"></span>
	<ul class="jx-bar-button-right">
		<?php $items = $this->Session->read('Compare.Item');?>
		<li alt="Compare <?php echo count($items) > 0 ? ' ('.count($items).' '.__((count($items) == 1) ? 'items' : 'item', true).')' : ''; ?>"><?php echo $this->Html->image('status_bar/compare.png', array('url' => array('plugin' => 'compare', 'controller' => 'items', 'action' => 'view'))); ?></li>
	</ul>
	<span class="jx-separator-right"></span>
</div>