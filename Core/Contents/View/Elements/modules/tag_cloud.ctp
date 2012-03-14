<?php
	if(!empty($config['model'])) {
		list($plugin,) = pluginSplit($config['model']);
		if($plugin != $this->request->plugin) {
			return;
		}
	}
	if(isset($config['category']) && $config['category'] && !empty($this->request->params['category'])) {
		$config['category'] = $this->request->params['category'];
	}
	
	if(!isset($tags)){
		$tags = ClassRegistry::init('Blog.BlogPost')->GlobalTagged->find(
			'cloud',
			array(
				'limit' => !empty($config['limit']) ? $config['limit'] : 50,
				'model' => !empty($config['model']) ? $config['model'] : null,
				'category' => !empty($config['category']) ? $config['category'] : null,
			)
		);
	}

	// format is different of views / the find above
	if(!isset($tags[0]['GlobalTag'])){
		$_tags = array();
		foreach($tags as $tag){
			$_tags[]['GlobalTag'] = $tag;
		}
		$tags = $_tags;
		unset($_tags);
	}
	
	if(!$tags) {
		return false;
	}
	
	$url = !empty($config['url']) ? $config['url'] : array();
	if(empty($url) && !empty($config['model'])) {
		list($plugin, $model) = pluginSplit($config['model']);
		$url['plugin'] = Inflector::underscore($plugin);
		$url['controller'] = Inflector::tableize($model);
		$url['action'] = 'index';
	}
	
	if(!empty($config['category'])) {
		$url['category'] = $config['category'];
	}
?>
<div class="side_box">
	<div class="title_bar"><?php echo $config['title']; ?></div>
	<div class="content">
		<?php
			echo $this->TagCloud->display(
				$tags,
				array(
					'before' => '<li size="%size%" class="tag">',
					'after'  => '</li>',
					'url' => $url,
					'named' => 'tag'
				)
			);
		?>
	</div>
</div>