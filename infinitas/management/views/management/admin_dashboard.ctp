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
	<h2><?php __('Admin Dashboard'); ?></h2>
	<div class="info">
		Welcome to infinitas
	</div>
	<div class="column-a">
		<div class="box tasks">
			<h3><?php __('Whats Happening'); ?></h3>
			<ul>
				<li>
					<div class="counts item" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/comments.png'); ?>)">
						<h4><?php __('Site Status'); ?></h4>
						<div class="count">
							<h3><?php __('Posts'); ?></h3>
							<?php
								echo $this->Design->niceBox();
									?><div><?php echo $data['Count']['Post']['total'].' | ', __('Total', true); ?></div>
									<div><?php echo $data['Count']['Post']['active'], ' | ', __('Published', true); ?></div>
									<div><?php echo $data['Count']['Post']['pending'], ' | ', __('Pending', true); ?></div><?php
								echo $this->Design->niceBoxEnd();
							?>
						</div>
						<div class="count">
							<h3><?php __('Content'); ?></h3>
							<?php
								echo $this->Design->niceBox();
									?><div><?php echo $data['Count']['Content']['total'].' | ', __('Total', true); ?></div>
									<div><?php echo $data['Count']['Content']['active'], ' | ', __('Published', true); ?></div>
									<div><?php echo $data['Count']['Content']['pending'], ' | ', __('Pending', true); ?></div><?php
								echo $this->Design->niceBoxEnd();
							?>
						</div>
						<div class="count">
							<h3><?php __('Comments'); ?></h3>
							<?php
								echo $this->Design->niceBox();
									?><div><?php echo $data['Count']['Comment']['total'].' | ', __('Total', true); ?></div>
									<div><?php echo $data['Count']['Comment']['active'], ' | ', __('Accepted', true); ?></div>
									<div><?php echo $data['Count']['Comment']['pending'], ' | ', __('Pending', true); ?></div>
									<div><?php echo $data['Count']['Comment']['spam'], ' | ', __('Spam', true); ?></div><?php
								echo $this->Design->niceBoxEnd();
							?>
						</div>
					</div>
				</li>
				<li>
					<div class="counts item" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/comments.png'); ?>)">
						<h4><?php __('Popular Content'); ?></h4>

						<div class="count">
							<h3><?php __('Posts'); ?></h3>
							<?php
								echo $this->Design->niceBox();
									foreach($data['popularPost'] as $post){
										?>
											<div>
												<?php
													echo $this->Html->link(
														$this->Text->truncate($post['Post']['title'], 30),
														array(
															'plugin' => 'blog',
															'controller' => 'posts',
															'action' => 'edit',
															$post['Post']['id']
														),
														array(
															'title' => sprintf('%s '.__('views', true), $post['Post']['views'])
														)
													);
												?>
											</div>
										<?php
									}
								echo $this->Design->niceBoxEnd();
							?>
						</div>
						<div class="count">
							<h3><?php __('Content'); ?></h3>
							<?php
								echo $this->Design->niceBox();
									foreach($data['popularContent'] as $content){
										?>
											<div>
												<?php
													echo $this->Html->link(
														$this->Text->truncate($content['Content']['title'], 30),
														array(
															'plugin' => 'cms',
															'controller' => 'contents',
															'action' => 'edit',
															$content['Content']['id']
														),
														array(
															'title' => sprintf('%s '.__('views', true), $content['Content']['views'])
														)
													);
												?>
											</div>
										<?php
									}
								echo $this->Design->niceBoxEnd();
							?>
						</div>
					</div>
				</li>
				<li>
					<div class="item" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/comments.png'); ?>)">
						<h4><?php __('Latest Comments'); ?></h4>
						<?php echo $this->element('admin/comments/latest', array('plugin' => 'management', 'comments' => $data['latestComment'])); ?>
					</div>
				</li>
				<li>
					<div class="item" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/comments.png'); ?>)">
						<h4><?php __('Latest Users'); ?></h4>
						<?php echo $this->element('admin/users/logged_in', array('plugin' => 'management', 'users' => $data['latestUser'])); ?>
					</div>
				</li>
				<!--<li>
					<a href="<?php echo $this->Html->url(array('action' => 'index')); ?>">
						<div class="item" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/page_copy.png'); ?>)">
							<h4>Manage blog posts</h4>
							<p>Navigate your blog and edit or publish blog posts</p>
						</div>
					</a>
				</li>-->
			</ul>
		</div>
	</div>
	<div class="column-b">
		<div class="box tasks">
			<h3><?php __('Time Savers'); ?></h3>
			<ul>
				<li>
					<div class="item" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/page_copy.png'); ?>)">
						<h4><?php __('Quick Post'); ?></h4>
						<?php echo $this->element('admin/quick_post', array('plugin' => Configure::read('Website.admin_quick_post'))); ?>
					</div>
				</li>
				<!--<li>
					<a href="<?php echo $this->Html->url(array('action' => 'index')); ?>">
						<div class="item" style="background-image: url(<?php echo $this->Html->url('/img/core/icons/fatcow/16/page_copy.png'); ?>)">
							<h4>Manage blog posts</h4>
							<p>Navigate your blog and edit or publish blog posts</p>
						</div>
					</a>
				</li>-->
			</ul>
		</div>
	</div>
	<div class="clr"></div>
</div>