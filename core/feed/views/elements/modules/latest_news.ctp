<?php
	if(!isset($feeds)){
		$config['feed'] = isset($config['feed']) ? $config['feed'] : '';
		$feeds = ClassRegistry::init('Feed.Feed')->getFeed($config['feed'], $this->Session->read('Auth.User.group_id'));
	}
	
    foreach($feeds as $feed){
		$model = Inflector::camelize(Inflector::singularize($feed['Feed']['controller']));
		$feed[$model] =& $feed['Feed'];

		$eventData = $this->Event->trigger('feedBeforeContentRender', array('_this' => $this, 'feed' => $feed));
		?><div class="beforeEvent"><?php
			foreach((array)$eventData['feedBeforeContentRender'] as $_plugin => $_data){
				echo '<div class="'.$_plugin.'">'.$_data.'</div>';
			}
			?></div>
			<div class="wrapper">
				<div class="introduction <?php echo $this->layout; ?>">
					<h2>
						<?php
							$eventData = $this->Event->trigger($feed['Feed']['plugin'].'.slugUrl', array('type' => $feed['Feed']['controller'], 'data' => $feed));
							$urlArray = current($eventData['slugUrl']);
							echo $this->Html->link(
								$feed['Feed']['title'],
								$urlArray
							);
						?><small><?php echo $this->Time->niceShort($feed['Feed']['date']); ?></small>
					</h2>
					<div class="content <?php echo $this->layout; ?>">
						<?php echo $this->Text->truncate($feed['Feed']['body'], 350, array('html' => true)); ?>
					</div>
				</div>
				<?php
					echo $this->element(
						'modules/comment',
						array(
							'plugin' => 'comments',
							'content' => $feed,
							'modelName' => $model,
							'foreign_id' => $feed['Feed']['id']
						)
					);
				?>
			</div>
			<div class="afterEvent">
				<?php
					$eventData = $this->Event->trigger('feedAfterContentRender', array('_this' => $this, 'feed' => $feed));
					foreach((array)$eventData['feedAfterContentRender'] as $_plugin => $_data){
						echo '<div class="'.$_plugin.'">'.$_data.'</div>';
					}
				?>
			</div>
		<?php
    }