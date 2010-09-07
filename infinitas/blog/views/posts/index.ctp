<?php
    /**
     * Blog index view file.
     *
     * Generate the index page for the blog posts
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    foreach($posts as $post){
		$eventData = $this->Event->trigger('blogBeforeContentRender', array('_this' => $this, 'post' => $post));
		foreach((array)$eventData['blogBeforeContentRender'] as $_plugin => $_data){
			echo '<div class="before '.$_plugin.'">'.$_data.'</div>';
		}
		?>
			<div class="wrapper">
				<div class="introduction <?php echo $this->layout; ?>">
					<h2><?php echo $post['Post']['title']; ?><span><?php echo $this->Time->niceShort($post['Post']['created']); ?></span></h2>
					<div class="content <?php echo $this->layout; ?>">
						<p><?php echo $this->Text->truncate($post['Post']['body'], 200, array('html' => true)); ?></p>
						<div class="tags"><?php echo $this->PostLayout->tags($post['Tag']); ?></div>
					</div>
				</div>
				<div class="comments">
					<?php
						echo $this->Html->link(
							__('View all comments', true),
							array(
								'plugin' => 'management',
								'controller' => 'comments',
								'action' => 'index',
								'Comment.class' => 'Post'
							)
						);
					?>
					<div class="comment">abc</div>
					<div class="comment">abc</div>
					<div class="comment">abc</div>
				</div>
			</div>
		<?php
		$eventData = $this->Event->trigger('blogAfterContentRender', array('_this' => $this, 'post' => $post));
		foreach((array)$eventData['blogAfterContentRender'] as $_plugin => $_data){
			echo '<div class="after '.$_plugin.'">'.$_data.'</div>';
		}
    }

    echo $this->element('pagination/navigation');
?>