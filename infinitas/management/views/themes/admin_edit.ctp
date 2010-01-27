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

    echo $this->Core->adminOtherHead( $this );
    echo $this->Form->create( 'Theme' );
        echo $this->Form->input( 'id' );
        echo $this->Form->input( 'name' );
        echo $this->Form->input( 'author' );
        echo $this->Form->input( 'url' );
        echo $this->Form->input( 'update_url' );
        echo $this->Form->input( 'licence' );
        echo $this->Form->input( 'active' );
        echo $this->Form->input( 'core' );
        echo $this->Core->wysiwyg( 'Theme.description' );
    echo $this->Form->end( 'Save Theme' );
 ?>