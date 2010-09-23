<?php
    /**
     * Management Modules admin edit post.
     *
     * this page is for admin to manage the modules on the site
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       management
     * @subpackage    management.views.configs.admin_edit
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    echo $this->Form->create('Branch', array('type' => 'file'));
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Branch', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('image', array('type' => 'file'));
			echo $this->Form->input('name');
			echo $this->Form->input('phone');
			echo $this->Form->input('fax');
			echo $this->Form->input('address', array('type' => 'textarea'));
			echo $this->Form->input('active'); ?>
		</fieldset>
	<?php
    echo $this->Form->end( );
?>