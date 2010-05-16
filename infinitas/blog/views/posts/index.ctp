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
		?>
			<div class="post <?php echo $this->layout; ?>">
				<h1><?php echo $post['Post']['title']; ?></h1>
				<p>
					<small>
						<?php
    						$temp = array();
							foreach(array('comments', 'date', 'views') as $param) {
								switch($param) {
									case 'date':
										$temp[] = sprintf('%s: %s', __('Created', true), $this->Time->{$this->Blog->dateFormat}($post['Post']['created']));
										break;

									case 'comments':
										$temp[] = sprintf('%s ( %s )', __('Comments', true), $post['Post']['comment_count']);
										break;

									case 'views':
										$temp[] = sprintf('%s ( %s )', __('Views', true), $post['Post']['views']);
										break;
								} // switch
							}
							if (!empty($temp)) {
								echo implode(' :: ', $temp);
							}
						?>
					</small>
				</p>

				<div class="content <?php echo $this->layout; ?>">
					<p><?php echo $this->Text->truncate($post['Post']['body'], 200, array('html' => true)); ?></p>

					<div class="tags"><?php echo $this->PostLayout->tags($post['Tag']); ?></div>
				</div>

				<div class="footer">
					<ul>
						<?php
							$out = '';
							foreach(array('print', 'comment', 'more') as $param) {
								$post['Post']['plugin'] = 'blog';
								$post['Post']['controller'] = 'posts';
								$post['Post']['action'] = 'view';
								$eventData = $this->Event->trigger('blog.slugUrl', array('type' => 'posts', 'data' => $post['Post']));
								switch($param) {
									case 'print':
										$out .= '<li class="printerfriendly"><a href="#">Printer Friendly</a></li>';
										break;

									case 'comment':
										$out .= '<li class="comments">' .
											$this->Html->link(
												sprintf('%s ( %s )', __('Comments', true), $post['Post']['comment_count']),
												current($eventData['slugUrl']) + array('#' => 'comments')
											).
										'</li>';
										break;

									case 'more':
										$out .= '<li class="readmore">' .
											$this->Html->link(
												__('Read more', true),
												current($eventData['slugUrl'])
											).
										'</li>';
										break;
								} // switch
							}
							echo $out;
						?>
					</ul>
				</div>
			</div>
		<?php
    }

    echo $this->element( 'pagination/navigation' );
?>