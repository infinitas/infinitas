<div class="feeds">
	<?php
		foreach( $feeds as $feed ){
			?>
				<div class="item <?php echo $feed['Feed']['controller']; ?>">
					<div class="title">
						<?php
							echo $this->Html->link(
								$feed['Feed']['title'],
								array(
									'plugin' => $feed['Feed']['plugin'],
									'controller' => $feed['Feed']['controller'],
									'action' => $feed['Feed']['action'],
									$feed['Feed']['id']
								)
							);
						?>
					</div>
					<div class="time"><?php echo $this->Time->niceShort( $feed['Feed']['created'] ); ?></div>
					<div class="clr">&nbsp;</div>
					<div class="body">
						<?php
							echo strip_tags( $feed['Feed']['intro'] );

							echo $this->Html->link(
								__( 'Read More', true ),
								array(
									'plugin' => $feed['Feed']['plugin'],
									'controller' => $feed['Feed']['controller'],
									'action' => $feed['Feed']['action'],
									$feed['Feed']['id']
								),
								array(
									'class' => 'readmore'
								)
							);
						?>
					</div>
				</div>
			<?php
		}
	?>
</div>