<?php
	/**
	 * Blog categories edit view
	 *
	 * The view for editing categories for blog posts
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package blog
	 * @subpackage blog.category.views.admin_add
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

    echo $this->Form->create( 'Category' );
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
	        ?>
				<div class="data">
					<?php
				        echo $this->Form->input('id');
				        echo $this->Blog->wysiwyg('Category.description');
				    ?>
				</div>
				<div class="config">
					<?php
						echo $this->Design->niceBox();
					        echo $this->Form->input('name', array( 'class' => 'title' ) );
					        echo $this->Form->input('active');
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>