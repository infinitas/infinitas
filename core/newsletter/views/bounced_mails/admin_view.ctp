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
							echo $this->EmailAttachments->isFlagged($bouncedMail['BouncedMail']),
								$this->Html->link($bouncedMail['From']['name'], 'mailto:'.$bouncedMail['From']['email']);
						?>
					</span>
				</div>
				<div class="email">
					<?php 
						if(!empty($bouncedMail['Email']['html'])){
							echo str_replace('<a ', '<a target="_blank" ', $bouncedMail['Email']['html']);
						}
						else{
							if(!empty($bouncedMail['Email']['text'])){
								echo $bouncedMail['Email']['text'];
							}
							else {
								echo $this->Text->autoLink(strip_tags(str_replace(array("\r\n", "\n"), '<br/>', $bouncedMail['Email']['html']), '<br>'));
							}
						}
					?>
					<hr>
					<?php echo $this->EmailAttachments->listAttachments($bouncedMail['Attachment']); ?>
				</div>
			</div>
		</div>
	<?
	echo $this->Form->end();