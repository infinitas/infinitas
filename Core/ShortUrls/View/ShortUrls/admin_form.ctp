<?php
/**
 * Create and edit short urls
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Infinitas.ShortUrls.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Form->create();
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');

	$tabs = array(
		__d('short_urls', 'Url')
	);

	$contents = array(
		$this->Form->input('url', array(
			'type' => 'text',
			'label' => __d('short_urls', 'Url to shorten')
		))
	);

	echo $this->Design->tabs($tabs, $contents);
echo $this->Form->end();