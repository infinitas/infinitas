<div class="introduction tags">
	<?php
		if((!isset($tags) || empty($tags)) && isset($post)){
			$tags = array();

			foreach($post['Tag'] as $tag){
				$tags[]['Tag'] = $tag;
			}
		}

		if(!empty($tags)){
			echo $this->TagCloud->display(
				$tags,
				array(
					'url' => array(
						'plugin' => 'blog',
						'controller' => 'posts',
						'action' => 'index'
					),
					'named' => 0
				)
			);
		}
		
		else{
			echo __('There are no tags for this post', true);
		}
	?>
</div>