<?php
    /**
     * Management Modules admin edit post.
     *
     * this page is for admin to manage the menu items on the site
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
     * @subpackage    management.views.menuItems.admin_edit
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */


	echo $this->Form->create( 'MenuItem' );
        $massActions = $this->Core->massActionButtons(
            array(
                'save',
            )
        );
        echo $this->Core->adminOtherHead( $this, $massActions );
        echo $this->Design->niceBox();
	        ?>
				<div class="data">
					<?php
						echo $this->Form->input( 'id' );
						echo $this->Form->input( 'name' );
						echo $this->Form->input( 'link', array('label' => __('External Link', true)) );
						echo $this->Form->input( 'prefix' );
						echo $this->Form->input( 'plugin' );
						echo $this->Form->input( 'controller' );
						echo $this->Form->input( 'action' );
						echo $this->Form->input( 'params', array('type' => 'textarea') );
				    ?>
				</div>
				<div class="config">
					<?php
						echo $this->Design->niceBox();
					        echo $this->Form->input( 'active' );
					        echo $this->Form->input( 'group_id' );
					        echo $this->Form->input( 'menu_id' );
					        echo $this->Form->input( 'parent_id' );
					        echo $this->Form->input( 'force_backend' );
					        echo $this->Form->input( 'force_frontend' );
					        echo $this->Form->input( 'class', array('type' => 'textarea', 'style' => 'width:50%;') );
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>