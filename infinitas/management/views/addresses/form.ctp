<?php
    /**
     * Management Config admin edit post.
     *
     * this page is for admin to manage the setup of the site
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

	echo $this->Form->create('Address');
        echo $this->Form->input('id');
        echo $this->Form->input('name');
        echo $this->Form->input('continent_id', array('empty' => Configure::read('Website.empty_select')));
        echo $this->Form->input('country_id', array('empty' => Configure::read('Website.empty_select')));
        echo $this->Form->input('province');
        echo $this->Form->input('city');
        echo $this->Form->input('street');
        echo $this->Form->input('postal');
        echo $this->Form->hidden('plugin');
        echo $this->Form->hidden('model');
        echo $this->Form->hidden('foreign_key');
	echo $this->Form->end(__('Save', true));
?>