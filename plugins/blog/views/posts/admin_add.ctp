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
     * @link          http://www.dogmatic.co.za
     * @package       blog
     * @subpackage    blog.views.posts.admin_add
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */


    echo $this->Blog->adminOtherHead( $this );
    echo $this->Form->create( 'Post' );
        echo $this->Form->input( 'title', array( 'class' => 'title' ) );
        echo $this->Form->input( 'active' );
        echo $this->Blog->wysiwyg( 'Post.intro' );
        echo $this->Blog->wysiwyg( 'Post.body' );
        echo $this->Form->input( 'Tag', array( 'multiple' =>  'checkbox' ) );
        echo $this->Form->input( 'new_tags', array( 'type' => 'textarea', 'rows' => 5, 'style' => 'width:100%;' ) );
    echo $this->Form->end( 'Save Post' );
?>