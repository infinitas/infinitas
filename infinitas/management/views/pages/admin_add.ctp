<?php
    /**
     * Management Page admin edit page.
     *
     * this page is for admin to edit static pages
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

    echo $this->Core->adminOtherHead( $this );
    echo $this->Form->create( 'Page', array( 'action' => 'add' ) );
?>
    <div>
<?php 
    echo $this->Form->input('Page.name', array('type' => 'text'));
    echo $this->Core->wysiwyg( 'Page.body' );
?>
	</div>
<?php 
    echo $this->Form->end( 'Save page' );
?>