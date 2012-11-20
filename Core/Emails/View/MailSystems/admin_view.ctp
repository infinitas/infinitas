<?php
echo $this->Form->create('MailSystem', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead(null, array(
			'reply',
			'forward',
			'unread',
			'delete',
			'back'
		)); ?>
	<div class="dashboard">
		<h1>
			<?php echo $mail['MailSystem']['subject']; ?>
			<small><?php echo $this->Infinitas->date($mail['MailSystem']['created']); ?></small>
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
	</div> <?php
echo $this->Form->end();