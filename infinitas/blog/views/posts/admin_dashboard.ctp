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
 * @link          http://www.dogmatic.co.za
 * @package       blog
 * @subpackage    blog.views.posts.dashboard
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<div class="dashboard">
	<h2><?php __('Blog Dashboard'); ?></h2>
	<div class="chart">
		<?php
			$this->Chart->debug = 1;
			echo $this->Chart->display(
				'pie3d',
				array(
					'data' => array($dashboardPostCount['active'], $dashboardPostCount['pending']),
					'labels' => array(__('Active', true), __('Pending', true)),
					'size' => array(250, 100),
					'title' => array(
						'text' => __('Active vs Pending', true)
					)
				)
			);
		?>
	</div>
	<div class="dashboard">
		<h2><?php __('Blog Feed'); ?></h2>
		<ul>
			<?php foreach($blogFeeds as $feed): ?>
				<li>
					<div class="item">
						<h3>
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
						</h3>
						<div class="time"><?php echo $this->Time->niceShort($feed['Feed']['created']); ?></div>
						<div class="preview"><?php echo $feed['Feed']['intro']; ?></div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
