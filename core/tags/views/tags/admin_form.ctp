<?php
    /**
     * Add and edit tags
     *
     * this page is for admin to manage tags on the site
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       infinitas.tags
     * @subpackage    infinitas.tags.views.admin_form
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 *
	 * @since 0.7a
     */

	echo $this->Form->create('Tag');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Tag', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('identifier');
			echo $this->Form->input('name', array('readonly' => 'readonly'));
			echo $this->Form->input('keyname'); ?>
		</fieldset><?php
    echo $this->Form->end();
?>