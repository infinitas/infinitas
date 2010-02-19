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

	echo $this->Form->create( 'User' );
		$massActions = $this->Core->massActionButtons(
			array(
				'save'
			)
		);

		echo $this->Core->adminOtherHead( $this, $massActions );
		echo $this->Design->niceBox();
			?>
			<div class="data">
				<?php
			        echo $this->Form->input('id');
			        echo $this->Form->input('username');
			        echo $this->Form->input('password', array('value' => ''));
			        echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => ''));
			        echo $this->Form->input('email');
			        echo $this->Form->input('birthday');
			    ?>
			</div>
			<div class="config">
				<?php
					echo $this->Design->niceBox();
				        echo $this->Form->input('active');
				        echo $this->Form->input('group_id');
					echo $this->Design->niceBoxEnd();
				?>
			</div>
			<?php
		echo $this->Design->niceBoxEnd();
	echo $this->Form->end();
?>