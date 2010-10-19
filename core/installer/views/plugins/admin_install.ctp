<?php
    /**
	 * Install additional plugins and thmese
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link http://infinitas-cms.org
     * @package Infinitas.categories
     * @subpackage Infinitas.categories.admin_form
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.5a
	 *
	 * @author dogmatic69
     */

	echo $this->Form->create('Install', array('type' => 'file'));
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Install From disk', true); ?></h1><?php
			echo $this->Form->input('file', array('type' => 'file')); ?>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Install From the web', true); ?></h1><?php
			echo $this->Form->input('url'); ?>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Install from Infinitas', true); ?></h1><?php
			echo __('Coming soon', true); ?>
		</fieldset><?php
	echo $this->Form->end();
?>