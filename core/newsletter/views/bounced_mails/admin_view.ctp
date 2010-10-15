<?php
    echo $this->Form->create('BouncedMail', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'reply',
                'forward',
                'unread',
                'delete',
                'back'
            )
        );
		echo $this->Infinitas->adminIndexHead(null, $massActions); ?>
		<div class="dashboard">
			<h1>
				<?php echo $bouncedMail['BouncedMail']['subject']; ?>
				<small><?php echo $this->Time->niceShort($bouncedMail['BouncedMail']['created']); ?></small>
			</h1>
			<div class="mail">
				<div class="sender">
					<span>
						<?php
							echo $this->Letter->isFlagged($bouncedMail['BouncedMail']),
								$this->Html->link($bouncedMail['BouncedMail']['from']['name'], 'mailto:'.$bouncedMail['BouncedMail']['from']['email']);
						?>
					</span>
				</div>
				<?php echo $bouncedMail['BouncedMail']['Email']['html']; ?>
			</div>
		</div>
	<?
	echo $this->Form->end();