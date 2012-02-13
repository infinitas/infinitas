<?php
	foreach($comments as $comment){
		?>
			<div class="comment">
				<div class="gravitar"><?php echo $this->Gravatar->image($comment['InfinitasComment']['email'], array('size' => '50')); ?></div>
				<div class="text">
					<p><?php echo $this->Text->truncate($comment['InfinitasComment']['comment'], 200); ?></p>
					<div class="actions">
						<?php
							$toggleText = $comment['InfinitasComment']['active'] ? 'Deactivate' : 'Activate';
							echo $this->Html->link(
								__($toggleText),
								array(
									'controller' => 'infinitas_comments',
									'action' => 'toggle',
									$comment['InfinitasComment']['id']
								),
								array('class' => 'accept')
							), ' | ',
							$this->Html->link(
								__('Reply'),
								array(
									'controller' => 'infinitas_comments',
									'action' => 'reply',
									$comment['InfinitasComment']['id']
								),
								array('class' => 'reply')
							), ' | ',
							$this->Html->link(
								__('Delete'),
								array(
									'controller' => 'infinitas_comments',
									'action' => 'delete',
									$comment['InfinitasComment']['id']
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