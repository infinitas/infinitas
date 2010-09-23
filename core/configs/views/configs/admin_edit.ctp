<?php
    /**
     * form to edit existing configuration values for infinitas
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
     * @subpackage Infinitas.configs.admin_edit
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.5a
	 *
	 * @author dogmatic69
     */

    echo $this->Form->create('Config', array('action' => 'edit'));
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Configuration', true); ?></h1><?php
			echo $this->Form->input('id' );
			echo $this->Form->input('key', array('readonly' => true));

			echo $this->Form->hidden('type');

			switch( $this->data['Config']['type']){
				case 'bool':
					$_label = explode('.', $this->data['Config']['key']);
					$label = (isset($_label[1]) ? $_label[1] : $_label[0]);
					$this->data['Config']['value'] = ($this->data['Config']['value'] == 'true') ? 1 : 0;
					echo $this->Form->input(
						'value',
						array(
							'type' => 'checkbox',
							'label' => Inflector::humanize( $label ),
							'checked' => $this->data['Config']['value'] == 1 ? 'checked' : '',
							'value' => $this->data['Config']['value']
						)
					);
					break;

				case 'dropdown':
					$_options = explode(',', $this->data['Config']['options']);
					foreach($_options as $o){
						$options[$o] = $o;
					}

					echo $this->Form->input(
						'value',
						array(
							'type' => 'select',
							'options' => $options,
							'selected' => $this->data['Config']['value']
						)
					);
					break;

				case 'integer':
				case 'string':
					echo $this->Form->input('value');
					break;
			} // switch ?>
		</fieldset><?php
    echo $this->Form->end();
?>