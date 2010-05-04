<div class="right-item">
    <div class="tags">
        <p>
            <?php
            	$tags = ClassRegistry::init('Blog.Post')->getTags();
				echo $this->TagCloud->display(
					$tags,
					array(
						'before' => '<li size="%size%" class="tag">',
						'after'  => '</li>'
					)
				);
			?>
        </p>
    </div>
</div>