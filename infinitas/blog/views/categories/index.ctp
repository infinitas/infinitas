<?php
    /**
     * Blog index view file.
     *
     * Generate the index page for the blog posts
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
     * @subpackage    blog.views.index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
    foreach( $posts as $data )
    {
        $this->PostLayout->setData( $data );

        echo $this->PostLayout->indexPostStart();
            echo $this->PostLayout->indexPostHead();
            echo $this->PostLayout->indexPostContent();
            echo $this->PostLayout->indexPostFooter();
        echo $this->PostLayout->indexPostEnd();
    }

    echo $this->element( 'pagination/navigation' );
?>