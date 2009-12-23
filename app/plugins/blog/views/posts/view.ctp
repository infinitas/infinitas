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
?>
<?php
    $this->PostLayout->setData( $post );
    echo $this->PostLayout->viewPostHead();
    echo $this->PostLayout->viewPostBody( array( 'highlight' ) );
?>

<div id="comments">
    <?php
        foreach( $post['Comment'] as $comment )
        {
            $this->CommentLayout->setData( $comment );
            echo $this->CommentLayout->showComment();
        }
        echo $this->CommentLayout->addComment( $post['Post']['id'] );
    ?>
</div>