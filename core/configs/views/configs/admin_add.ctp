<?php
    /**
     * Management Config admin add configs.
     *
     * This page is to add configuration vars that will be loaded automaticaly
     * on startup
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
     * @subpackage    management.views.configs.admin_add
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.5a
     */

	echo $this->Form->create('Config');
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
	        ?>
				<div class="data">
					<?php
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
				        echo $this->Form->input('options', array('class' => 'title'));
				        echo $this->Form->input('core');
				    ?>
				</div>
				<div class="config">
					<?php
						echo $this->Design->niceBox();
					        echo $this->Core->wysiwyg('Config.description');
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>