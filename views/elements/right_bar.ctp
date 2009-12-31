<div id="secondarycontent">
    <?php
        if ( isset( $tagCount ) )
        {
            echo $this->element( 'tag_cloud', array( 'plugin' => 'blog', 'tagCount' => $tagCount ) );
        }

        if ( isset( $postDates ) )
        {
            echo $this->element( 'post_dates', array( 'plugin' => 'blog', 'postDates' => $postDates ) );
        }
    ?>
</div>