<div class="right-item">
    <div class="tags">
        <p>
            <?php
            	$tagCount = ClassRegistry::init('Blog.Tag')->getCount();
				echo $this->TagCloud->display(
					$tagCount,
					array(
						'model' => 'Tag',
						'url' => array('plugin' => 'blog', 'controller' => 'posts', 'action' => 'index')
					)
				);
			?>
        </p>
    </div>
</div>