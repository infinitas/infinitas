<?php
    /**
     * Blog Comments admin add new post
     *
     * this is the page for admins to add new blog posts
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.posts.admin_add
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    echo $this->Form->create('Post');
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
	        ?>
				<div class="data">
					<?php
				        echo $this->Form->input('id');
					    echo $this->Form->input('title', array( 'class' => 'title' ) );
				        echo $this->Blog->wysiwyg('Post.body');
				    ?>
				</div>
				<div class="config">
					<?php
						echo $this->Design->niceBox();
					        echo $this->element('category_list', array('plugin' => 'Categories'));
					        echo $this->Form->input('parent_id', array('empty' => __('No Parent', true)));
					        echo $this->Form->input('active');
					        echo $this->Form->input( 'tags' );
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>