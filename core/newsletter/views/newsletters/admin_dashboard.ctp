<?php
	if(!($hasCampaign && $hasTemplate && $hasNewsletter)) { ?>
		<div class="dashboard grid_16">
			<h1><?php echo __d('newsletter', 'Please setup the Newsletter plugin before use', true); ?></h1>
			<?php
				if(!$hasTemplate) { ?>
					<p class="info">
						<?php
							echo sprintf(
								__d('newsletter', 'Create a %s', true),
								$this->Html->link(
									__d('newsletter', 'template', true),
									array(
										'controller' => 'templates',
										'action' => 'add'
									)
								)
							);
						?>
					</p> <?php
				}

				if(!$hasCampaign) { ?>
					<p class="info">
						<?php
							echo sprintf(
								__d('newsletter', 'Create your first %s', true),
								$this->Html->link(
									__d('newsletter', 'campaign', true),
									array(
										'controller' => 'campaigns',
										'action' => 'add'
									)
								)
							);
						?>
					</p> <?php
				}

				if(!$hasNewsletter) { ?>
					<p class="info">
						<?php
							echo sprintf(
								__d('newsletter', 'Create your first %s', true),
								$this->Html->link(
									__d('newsletter', 'newsletter', true),
									array(
										'controller' => 'newsletters',
										'action' => 'add'
									)
								)
							);
						?>
					</p> <?php
				}
			?>
		</div> <?php
		return;
	}

	$links = array();
	$links['main'] = array(
		array(
			'name' => __d('newsletter', 'Campaigns', true),
			'description' => __d('newsletter', 'Create and manage your email Campaigns', true),
			'icon' => '/newsletter/img/icon/campaign.png',
			'dashboard' => array('controller' => 'campaigns', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Templates', true),
			'description' => __d('newsletter', 'Create and manage your email Templates', true),
			'icon' => '/newsletter/img/icon/template.png',
			'dashboard' => array('controller' => 'templates', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Newsletters', true),
			'description' => __d('newsletter', 'Create and manage your Newsletters', true),
			'icon' => '/newsletter/img/icon/newsletter.png',
			'dashboard' => array('controller' => 'newsletters', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Subscribers', true),
			'description' => __d('newsletter', 'View and manage your list of subscribers', true),
			'icon' => '/newsletter/img/icon/subscribers.png',
			'dashboard' => array('controller' => 'subscribers', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Bounced Mail', true),
			'description' => __d('newsletter', 'View bounced mail', true),
			'icon' => '/newsletter/img/icon/bounce.png',
			'dashboard' => array('controller' => 'bounced_mail', 'action' => 'index')
		),
	);

	$links['config'] = array(
		array(
			'name' => __d('newsletter', 'Modules', true),
			'description' => __d('newsletter', 'Manage content modules', true),
			'icon' => '/modules/img/icon.png',
			'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.plugin' => 'Newsletter')
		),
		array(
			'name' => __d('newsletter', 'Locked', true),
			'description' => __d('newsletter', 'Manage locked content', true),
			'icon' => '/locks/img/icon.png',
			'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index', 'Lock.class' => 'Newsletter')
		),
		array(
			'name' => __d('newsletter', 'Trash', true),
			'description' => __d('newsletter', 'View / Restore previously removed content', true),
			'icon' => '/trash/img/icon.png',
			'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index', 'Trash.model' => 'Newsletter')
		)
	);

	if($this->Infinitas->hasPlugin('ViewCounter')) {
		$links['config'][] =  array(
			'name' => __d('newsletter', 'Views', true),
			'description' => __d('newsletter', 'Track content popularity', true),
			'icon' => '/view_counter/img/icon.png',
			'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counts', 'action' => 'reports', 'ViewCount.model' => 'Newsletter')
		);
	}
?>
<div class="dashboard grid_16">
	<h1><?php __d('newsletter', 'Email Campaigns'); ?></h1>
	<?php echo $this->Design->arrayToList(current($this->Menu->builDashboardLinks($links['main'], 'newsletter_main_icons')), 'icons'); ?>
	<p class="info"><?php echo Configure::read('Newsletter.info.campaigns'); ?></p>
</div>
<div class="dashboard grid_16">
	<h1><?php __d('newsletter', 'Config / Data'); ?></h1>
	<?php echo $this->Design->arrayToList(current($this->Menu->builDashboardLinks($links['config'], 'newsletter_config_icons')), 'icons'); ?>
	<p class="info"><?php echo Configure::read('Newsletter.info.config'); ?></p>
</div>
<?php
	echo $this->element(
		'modules/admin/popular_items',
		array(
			'plugin' => 'view_counter',
			'model' => 'Newsletter.Newsletter'
		)
	);