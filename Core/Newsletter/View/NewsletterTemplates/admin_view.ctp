<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
echo $this->Infinitas->adminOtherHead();

echo $this->Html->tag('h3',  __d('newsletter', 'Campaigns set to use this template'));
if (empty($newsletterTemplate['NewsletterCampaign'])) {
	$newsletterTemplate['NewsletterCampaign'] = array(
		array('name' => __d('newsletter', 'There are no linked campaigns'))
	);
}
echo $this->Design->arrayToList(Hash::extract($newsletterTemplate['NewsletterCampaign'], '{n}.name'));

echo $this->Html->tag('h3',  __d('newsletter', 'Newsletters using this template'));
if (empty($newsletterTemplate['NewsletterTemplate'])) {
	$newsletterTemplate['NewsletterTemplate'] = array(
		array('subject' => __d('newsletter', 'There are no linked newsletters'))
	);
}
echo $this->Design->arrayToList(Hash::extract($newsletterTemplate['NewsletterTemplate'], '{n}.subject'));

echo $this->Html->tag('h3', __d('newsletter', 'Template Preview'));
echo $this->Letter->preview($newsletterTemplate['NewsletterTemplate']['id'], 'newsletter_templates');