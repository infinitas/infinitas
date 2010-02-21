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

    echo $this->Infinitas->adminOtherHead($this);
    echo $this->Form->create('Branch', array('type' => 'file'));
    ?>
		<div style="width:50%; float:left;">
			<?php
				echo $this->Form->input('id');
		        echo $this->Form->input('image', array('type' => 'file'));
		        echo $this->Form->input('name');
		        echo $this->Form->input('phone');
		        echo $this->Form->input('fax');
		        echo $this->Form->input('address');
		        echo $this->Form->input('map');
		        echo $this->Form->input('active');
			?>
		</div>
		<div style="width:50%; float:left;">
			<?php
			?>
		</div>
		<div class="clr">&nbsp;</div>
	<?php
    echo $this->Form->end( 'Save Module' );
?>