<?php
	if(!($hasCampaign && $hasTemplate && $hasNewsletter)) { ?>
		<div class="dashboard grid_16">
			<h1><?php echo __d('newsletter', 'Please setup the Newsletter plugin before use'); ?></h1>
			<?php
				if(!$hasTemplate) { ?>
					<p class="info">
						<?php
							echo sprintf(
								__d('newsletter', 'Create a %s'),
								$this->Html->link(
									__d('newsletter', 'template'),
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
								__d('newsletter', 'Create your first %s'),
								$this->Html->link(
									__d('newsletter', 'campaign'),
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
								__d('newsletter', 'Create your first %s'),
								$this->Html->link(
									__d('newsletter', 'newsletter'),
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
			'name' => __d('newsletter', 'Campaigns'),
			'description' => __d('newsletter', 'Create and manage your email Campaigns'),
			'icon' => '/newsletter/img/icon/campaign.png',
			'dashboard' => array('controller' => 'campaigns', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Templates'),
			'description' => __d('newsletter', 'Create and manage your email Templates'),
			'icon' => '/newsletter/img/icon/template.png',
			'dashboard' => array('controller' => 'templates', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Newsletters'),
			'description' => __d('newsletter', 'Create and manage your Newsletters'),
			'icon' => '/newsletter/img/icon/newsletter.png',
			'dashboard' => array('controller' => 'newsletters', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Subscribers'),
			'description' => __d('newsletter', 'View and manage your list of subscribers'),
			'icon' => '/newsletter/img/icon/subscribers.png',
			'dashboard' => array('controller' => 'subscribers', 'action' => 'index')
		),
		array(
			'name' => __d('newsletter', 'Bounced Mail'),
			'description' => __d('newsletter', 'View bounced mail'),
			'icon' => '/newsletter/img/icon/bounce.png',
			'dashboard' => array('controller' => 'bounced_mail', 'action' => 'index')
		),
	);

	$links['config'] = array(
		array(
			'name' => __d('newsletter', 'Modules'),
			'description' => __d('newsletter', 'Manage content modules'),
			'icon' => '/modules/img/icon.png',
			'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.plugin' => 'Newsletter')
		),
		array(
			'name' => __d('newsletter', 'Locked'),
			'description' => __d('newsletter', 'Manage locked content'),
			'icon' => '/locks/img/icon.png',
			'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index', 'Lock.class' => 'Newsletter')
		),
		array(
			'name' => __d('newsletter', 'Trash'),
			'description' => __d('newsletter', 'View / Restore previously removed content'),
			'icon' => '/trash/img/icon.png',
			'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index', 'Trash.model' => 'Newsletter')
		)
	);

	if($this->Infinitas->hasPlugin('ViewCounter')) {
		$links['config'][] =  array(
			'name' => __d('newsletter', 'Views'),
			'description' => __d('newsletter', 'Track content popularity'),
			'icon' => '/view_counter/img/icon.png',
			'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'reports', 'ViewCount.model' => 'Newsletter')
		);
	}
?>
<div class="dashboard grid_16">
	<h1><?php echo __d('newsletter', 'Email Campaigns'); ?></h1>
	<?php echo $this->Design->arrayToList(current($this->Menu->builDashboardLinks($links['main'], 'newsletter_main_icons')), 'icons'); ?>
	<p class="info"><?php echo Configure::read('Newsletter.info.campaigns'); ?></p>
</div>
<div class="dashboard grid_16">
	<h1><?php echo __d('newsletter', 'Config / Data'); ?></h1>
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