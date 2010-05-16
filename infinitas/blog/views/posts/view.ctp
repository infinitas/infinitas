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
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.posts.view
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<h1><?php echo $post['Post']['title']; ?></h1>
<p>
	<small>
		<?php
			foreach(array('comments', 'date', 'views') as $param) {
				switch($param) {
					case 'date':
						$temp[] = sprintf('%s: %s', __('Created', true), $this->Time->{$this->Blog->dateFormat}($post['Post']['created']));
						break;

					case 'comments':
						$temp[] = sprintf('%s ( %s )', __('Comments', true), $post['Post']['comment_count']);
						break;

					case 'views':
						$temp[] = sprintf('%s ( %s )', __('Views', true), $post['Post']['views']);
						break;
				} // switch
			}
			if (!empty($temp)) {
				echo implode(' :: ', $temp);
			}
		?>
	</small>
</p>
<?php
    if (
        Configure::read('Blog.depreciate') &&
        date( 'Y-m-d H:i:s', strtotime('- '.Configure::read( 'Blog.depreciate'))) > $post['Post']['modified']
        ){
        ?><h2><?php __('Depreciated'); ?> </h2><?php
        echo __( 'This post is old, so the information may be a bit out-dated.', true );
    }

    $body = $post['Post']['body'];
	if (Configure::read('Blog.highlight') === true) {
		$body = $this->Geshi->highlight($body);
	}

	echo $body;

    echo $this->Blog->pagination($post);

    if (false && Configure::read('Blog.allow_comments')){
        if (
            !Configure::read('Blog.allow_comments') ||
            date('Y-m-d H:i:s', strtotime('- '.Configure::read('Comments.time_limit'))) < $post['Post']['modified']
        ){
            ?>
                <div id="comments">
                    <?php
                        if (empty($post['Comment'])){
                            ?><h2><?php __('No Comments'); ?> </h2><?php
                            echo '<p>'.__('There are no comments at this time, would you like to be the first?', true).'</p>';
                        }
                        else{
                            foreach($post['Comment'] as $comment){
                                $this->CommentLayout->setData($comment);
                                echo $this->CommentLayout->showComment();
                            }
                        }

                        echo $this->element('global/comment_add', array('fk' => $post['Post']['id']));
                    ?>
                </div>
            <?php
        }
        else{
            ?><h2><?php __('Closed for Comments'); ?> </h2><?php
            echo __('Sorry, the comments for this post are closed. Why not check out some of our newer posts.', true);
        }
    }
?>