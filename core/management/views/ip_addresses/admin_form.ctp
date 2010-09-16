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
     * @subpackage    management.views.menuItems.admin_add
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */


	echo $this->Form->create( 'IpAddress' );
        echo $this->Infinitas->adminEditHead();
        echo $this->Design->niceBox();
	        ?>
				<div class="data">
					<?php
						echo $this->Form->input('id');
						echo $this->Form->input('ip_address', array('style' => 'width:99%; clear:both;'));
						echo $this->Form->input('active');
						echo $this->Core->wysiwyg('IpAddress.description');
				    ?>
				</div>
				<div class="config">
					<?php
						echo $this->Design->niceBox();
						?>
							<p>
								<?php
									echo __('You can enter a normal ip addres here to '.
										'block it. You can also use wild cards like '.
										'123.25..*..* (the 2 dots is not a mistake) '.
										'or you can enter a regex expresion that will '.
										' be used in php\'s ereg() method to block ranges.', true);
								?>
							</p>
						<?php
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>