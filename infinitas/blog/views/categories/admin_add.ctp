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

    echo $this->Form->create( 'Post' );
        $massActions = $this->Blog->massActionButtons(
            array(
                'save',
            )
        );
        echo $this->Blog->adminOtherHead( $this, $massActions );
        echo $this->Design->niceBox();
	        ?>
				<div class="data">
					<?php
				        echo $this->Form->input('id');
				        echo $this->Blog->wysiwyg('Post.intro');
				        echo $this->Blog->wysiwyg('Post.body');
				    ?>
				</div>
				<div class="config">
					<?php
						echo $this->Design->niceBox();
					        echo $this->Form->input('title', array( 'class' => 'title' ) );
					        echo $this->Form->input('category_id', array('empty' => Configure::read('Website.empty_select')));
					        echo $this->Form->input('parent_id', array('empty' => __('No Parent', true)));
					        echo $this->Form->input('active');
					        ?>
								<div style="clear:both; overflow:auto;">
									<h3><?php __('Tags'); ?></h3>
									<?php echo $this->Form->input( 'Tag', array( 'label' => false, 'multiple' =>  'checkbox' ) ); ?>
								</div>
							<?php
					        echo $this->Form->input( 'new_tags', array( 'type' => 'textarea', 'rows' => 5, 'style' => 'width:70%' ) );
				        echo $this->Design->niceBoxEnd();
				    ?>
				</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>