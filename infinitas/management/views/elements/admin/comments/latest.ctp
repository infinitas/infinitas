<?php
	foreach($comments as $comment){
		?>
			<div class="comment">
				<div class="gravitar"><?php echo $this->Gravatar->image($comment['Comment']['email'], array('size' => '50')); ?></div>
				<div class="text">
					<h3><?php echo $this->Html->link($comment['Comment']['name'], $comment['Comment']['website'], array('target' => '_blank')); ?></h3>
					<p><?php echo $comment['Comment']['comment']; ?></p>
					<div class="actions">
						<?php
							$toggleText = $comment['Comment']['active'] ? 'Deactivate' : 'Activate';
							echo $this->Html->link(
								__($toggleText, true),
								array(
									'controller' => 'comments',
									'action' => 'toggle',
									$comment['Comment']['id']
								),
								array('class' => 'accept')
							), ' | ',
							$this->Html->link(
								__('Reply', true),
								array(
									'controller' => 'comments',
									'action' => 'reply',
									$comment['Comment']['id']
								),
								array('class' => 'reply')
							), ' | ',
							$this->Html->link(
								__('Delete', true),
								array(
									'controller' => 'comments',
									'action' => 'delete',
									$comment['Comment']['id']
								),
								array('class' => 'delete')
							);
						?>
					</div>
				</div>
			</div>
		<?php
	}
?>