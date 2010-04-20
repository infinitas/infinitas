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

    echo $this->Form->create( 'Module' );
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
	        ?>
				<div class="data">
					<?php
				        echo $this->Form->input( 'id' );
				        echo $this->Form->input( 'name' );
				        echo $this->Form->input( 'module' );
				        echo $this->Form->input( 'theme_id' );
				        echo $this->Form->input( 'position_id' );
				        echo $this->Form->input( 'group_id' );

				        echo $this->Form->input( 'content', array('class' => 'title') );
				        echo $this->Form->input( 'config', array('class' => 'title') );
				    ?>
				</div>
				<div class="config">
					<?php
						echo $this->Design->niceBox();
					        echo $this->Form->input( 'author' );
					        echo $this->Form->input( 'url' );
					        echo $this->Form->input( 'update_url' );
					        echo $this->Form->input( 'licence' );
					        echo $this->Form->input( 'active' );
					        echo $this->Form->input( 'show_heading' );
					        echo $this->Form->input( 'core' );
							echo $this->Form->input('Route', array('selected' => 0));
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end( );
?>