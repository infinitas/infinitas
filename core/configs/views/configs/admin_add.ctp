<?php
    /**
     * form to create new configuration values for infinitas
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link http://infinitas-cms.org
     * @package Infinitas.configs
     * @subpackage Infinitas.configs.admin_add
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.5a
	 *
	 * @author dogmatic69
     */

	echo $this->Form->create('Config');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Configuration', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('key');
			echo $this->Form->input('value');
			echo $this->Form->input(
				'type',
				array(
					'value' => $types,
					'type' => 'select',
					'selected' => isset($this->data['Config']['type']) ? $this->data['Config']['type'] : ''
				)
			);
			echo $this->Form->input('options', array('class' => 'title')); ?>
		</fieldset>
	<?php echo $this->Form->end(); ?>