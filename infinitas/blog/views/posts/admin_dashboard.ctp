<?php
	/**
	 * Blog Comments admin dashboard.
	 *
	 * this is the page for admin dashboard of the blog plugin.
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
	 * @subpackage    blog.views.posts.dashboard
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 */
?>
<div class="dashboard">
	<h2><?php __('Blog Dashboard'); ?></h2>
	<div class="info">
		The Blog Dashboard provides a general overview for <?php echo $this->Html->link('your blog', array('admin' => false, 'action' => 'index')); ?> &ndash;
		from here you can perform common blog tasks and view your blog's most recent activity.
	</div>
	<div class="column-a">
		<div class="box tasks">
			<h3><?php __('Common Tasks'); ?></h3>
			<ul>
				<li>
					<a href="<?php echo $this->Html->url(array('action' => 'add')); ?>">
						<div style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/page_add.png'); ?>)">
							<h4>Create a blog post</h4>
							<p>Create a blog post for your site</p>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('action' => 'index')); ?>">
						<div style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/page_copy.png'); ?>)">
							<h4>Manage blog posts</h4>
							<p>Navigate your blog and edit or publish blog posts</p>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(); ?>">
						<div style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/comments.png'); ?>)">
							<h4>Manage comments</h4>
							<p>Manage comments by approving or marking as spam</p>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(); ?>">
						<div style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/cog.png'); ?>)">
							<h4>Maintain your blog</h4>
							<p>Quickly perform tasks on your blog</p>
						</div>
					</a>
				</li>
			</ul>
		</div>
		<div class="box charts">
			<h3><?php __('Data overview'); ?></h3>
			<ul>
				<li class="first">
					<h4><strong>Blog posts</strong>: active vs. pending</h4>
					<?php
						echo $this->Chart->display(
							'pie3d',
							array(
								'data' => array($dashboardPostCount['active'], $dashboardPostCount['pending']),
								'labels' => array(__('Active', true), __('Pending', true)),
								'size' => array(200, 70),
							)
						);
					?>
				</li>
				<li>
					<h4><strong>Comments</strong>: active vs. pending</h4>
					<?php
						echo $this->Chart->display(
							'pie3d',
							array(
								'data' => array($commentCount['active'], $commentCount['pending']),
								'labels' => array(__('Active', true), __('Pending', true)),
								'size' => array(200, 70),
							)
						);
					?>
				</li>
			</ul>
		</div>
	</div>
	<div class="column-b">
		<div class="box blog-feed clr">
			<h3 class="left"><?php __('Recent activity'); ?></h3>
			<div class="view-all left">&nbsp;<?php echo $this->Html->link('(view all)', array('controller' => 'posts')); ?></div>
			<ul class="feed clr">
				<?php
					if($blogFeeds){
						foreach($blogFeeds as $feed){
							if (!isset($iteration)) {
								$iteration = 0;
							}

							$iteration++;
							$icon = 'page';

							if ($feed['Feed']['controller'] == 'comments') {
								$icon = 'comment';
							}

							?>
								<li class="clr <?php if ($iteration % 2) echo 'alternate'; ?>" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/' . $icon . '.png'); ?>);">
									<div class="item">
										<h4>
											<strong><?php echo Inflector::singularize(ucfirst($feed['Feed']['controller'])); ?></strong>:
											<?php
												echo $this->Html->link(
													$feed['Feed']['title'],
													array(
														'plugin' => $feed['Feed']['plugin'],
														'controller' => $feed['Feed']['controller'],
														'action' => $feed['Feed']['action'],
														$feed['Feed']['id']
													)
												);
											?>
										</h4>
										<div class="time quiet"><?php echo $this->Time->niceShort($feed['Feed']['created']); ?></div>
										<div class="preview clr"><?php echo $this->Text->truncate($feed['Feed']['intro'], 150, array('exact' => false, 'html' => true)); ?></div>
									</div>
								</li>
							<?php
						}
					}
				?>
			</ul>
		</div>
	</div>
	<div class="clr"></div>
</div>