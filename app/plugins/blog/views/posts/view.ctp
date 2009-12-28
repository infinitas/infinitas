<?php
    /**
     * Blog Comments view
     *
     * this is the page for users to view blog posts
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
     * @subpackage    blog.views.posts.view
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    $this->PostLayout->setData( $post );
    echo $this->PostLayout->viewPostHead();
    echo $this->PostLayout->viewPostBody( array( 'highlight' ) );

    if ( Configure::read( 'Blog.allow_comments' ) )
    {
        ?>
            <div id="comments">
                <?php
                    if ( !empty( $post['Comment'] ) )
                    {
                        ?><h2><?php __( 'No Comments' ); ?> </h2><?php
                        echo __( 'There are no comments at this time, would you like to be the first?', true );
                    }
                    else
                    {
                        foreach( $post['Comment'] as $comment )
                        {
                            $this->CommentLayout->setData( $comment );
                            echo $this->CommentLayout->showComment();
                        }
                    }

                    echo $this->element( 'comments/add', array( 'plugin' => 'core', 'fk' => $post['Post']['id'] ) );
                ?>
            </div>
        <?php
    }
?>