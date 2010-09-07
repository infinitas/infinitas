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
		?><div class="beforeEvent"><?php
			foreach((array)$eventData['blogBeforeContentRender'] as $_plugin => $_data){
				echo '<div class="'.$_plugin.'">'.$_data.'</div>';
			}
			?></div>
			<div class="wrapper">
				<div class="introduction <?php echo $this->layout; ?>">
					<h2>
						<?php
							$eventData = $this->Event->trigger('blog.slugUrl', array('type' => 'posts', 'data' => $post));
							$urlArray = current($eventData['slugUrl']);
							echo $this->Html->link(
								$post['Post']['title'],
								$urlArray
							);
						?><span><?php echo $this->Time->niceShort($post['Post']['created']); ?></span>
					</h2>
					<div class="content <?php echo $this->layout; ?>">
						<p><?php echo $this->Text->truncate($post['Post']['body'], 200, array('html' => true)); ?></p>						
					</div>
				</div>
				<?php
					echo $this->element(
						'modules/tags',
						array(
							'plugin' => 'blog',
							'post' => $post
						)
					);
					
					echo $this->element(
						'modules/comment',
						array(
							'plugin' => 'comment',
							'content' => $post,
							'modelName' => 'Post',
							'foreign_id' => $post['Post']['id']
						)
					);
				?>
			</div>
			<div class="afterEvent">
				<?php
					$eventData = $this->Event->trigger('blogAfterContentRender', array('_this' => $this, 'post' => $post));
					foreach((array)$eventData['blogAfterContentRender'] as $_plugin => $_data){
						echo '<div class="'.$_plugin.'">'.$_data.'</div>';
					}
				?>
			</div>
		<?php
    }

    echo $this->element('pagination/navigation');
?>