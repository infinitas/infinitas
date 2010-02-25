<?php
	/**
	 * Static Page admin add
	 *
	 * Adding new static pages
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.views.admin_add
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dakota
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
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