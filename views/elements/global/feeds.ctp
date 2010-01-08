<div class="feeds">
	<?php
		foreach( $feeds as $feed ){
			?>
				<div class="item <?php echo $feed['Feed']['controller']; ?>">
					<div class="title">
						<?php echo $feed['Feed']['title']; ?>
					</div>
					<div class="body">
						<?php echo strip_tags( $feed['Feed']['intro'] ); ?>
						<div class="readmore">
							<?php
								echo $this->Html->link(
									__( 'Read More', true ),
									array(
										'plugin' => $feed['Feed']['plugin'],
										'controller' => $feed['Feed']['controller'],
										'action' => $feed['Feed']['action'],
										$feed['Feed']['id']
									)
								)
							?>
						</div>
					</div>
				</div>
			<?php
		}
	?>
</div>