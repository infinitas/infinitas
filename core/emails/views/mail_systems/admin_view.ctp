<?php
    echo $this->Form->create('MailSystem', array('action' => 'mass'));
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
				<?php echo $mail['MailSystem']['subject']; ?>
				<small><?php echo $this->Time->niceShort($mail['MailSystem']['created']); ?></small>
			</h1>
			<div class="mail">
				<div class="sender">
					<span>
						<?php
							echo $this->EmailAttachments->isFlagged($mail['MailSystem']),
								$this->Html->link($mail['From']['name'], 'mailto:'.$mail['From']['email']);
						?>
					</span>
				</div>
				<div class="email">
					<?php echo $this->EmailAttachments->outputBody($mail); ?>
					<hr>
					<?php echo $this->EmailAttachments->listAttachments($mail['Attachment']); ?>
				</div>
			</div>
		</div>
	<?
	echo $this->Form->end();