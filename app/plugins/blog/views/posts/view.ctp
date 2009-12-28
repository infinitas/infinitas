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

    if (
        Configure::read( 'Blog.depreciate' ) &&
        date( 'Y-m-d H:i:s', strtotime( '- '.Configure::read( 'Blog.depreciate' ) ) ) > $post['Post']['modified']
        )
    {
        ?><h2><?php __( 'Depreciated' ); ?> </h2><?php
        echo __( 'This post is old, so the information may be a bit out-dated.', true );
    }

    echo $this->PostLayout->viewPostBody( array( 'highlight' ) );

    if ( Configure::read( 'Blog.allow_comments' ) )
    {
        if (
            !Configure::read( 'Blog.allow_comments' ) ||
            date( 'Y-m-d H:i:s', strtotime( '- '.Configure::read( 'Comments.time_limit' ) ) ) < $post['Post']['modified']
        )
        {
            ?>
                <div id="comments">
                    <?php
                        if ( empty( $post['Comment'] ) )
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
        else
        {
            ?><h2><?php __( 'Closed for Comments' ); ?> </h2><?php
            echo __( 'Sorry, the comments for this post is closed. Why not check out some of our newer posts.', true );
        }
    }
?>