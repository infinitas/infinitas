<?php
if (!($hasCampaign && $hasTemplate && $hasNewsletter)) { ?>
	<div class="dashboard grid_16">
		<h1><?php echo __d('newsletter', 'Please setup the Newsletter plugin before use'); ?></h1>
		<?php
			if (!$hasTemplate) { ?>
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

			if (!$hasCampaign) { ?>
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

			if (!$hasNewsletter) { ?>
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
		'dashboard' => array('controller' => 'newsletter_subscribers', 'action' => 'index')
	),
	array(
		'name' => __d('newsletter', 'Bounced'),
		'description' => __d('newsletter', 'View bounced mail'),
		'icon' => '/newsletter/img/icon/bounce.png',
		'dashboard' => array('controller' => 'bounced_mail', 'action' => 'index')
	),
);

foreach ($links as $name => &$link) {
	$link = $this->Design->arrayToList(current((array)$this->Menu->builDashboardLinks($link, 'newsletter_dashboard_' . $name)), array(
		'ul' => 'icons'
	));
}

echo $this->Design->dashboard($links['main'], __d('newsletter', 'Email Campaigns'), array(
	'class' => 'dashboard span6',
	'info' => Configure::read('Newsletter.info.campaigns')
));

echo $this->ModuleLoader->loadDirect('Contents.dashboard_links', array(
	'options' => array(
		'layouts' => false,
		'categories' => false,
		'routes' => false,
	)
));

echo $this->Html->tag('div', '', array('class' => 'clearfix'));

echo $this->ModuleLoader->loadDirect('ViewCounter.popular_items', array(
	'model' => 'Newsletter.Newsletter'
));