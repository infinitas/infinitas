<?php
/**
 * NewsletterSubscription
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 *
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

/**
 * NewsletterSubscription
 *
 * @package Infinitas.Newsletter.Model
 *
 * @property NewsletterSubscriber $NewsletterSubscriber
 * @property NewsletterCampaign $NewsletterCampaign
 */

class NewsletterSubscription extends NewsletterAppModel {

	public $hasMany = array(
		'NewsletterSubscriber' => array(
			'className' => 'Newsletter.NewsletterSubscriber'
		),
		'NewsletterCampaign' => array(
			'className' => 'Newsletter.NewsletterCampaign'
		)
	);
}
