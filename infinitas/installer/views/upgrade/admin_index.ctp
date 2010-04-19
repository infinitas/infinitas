<?php
	echo $this->Form->create( 'MenuItem' );
	echo $this->Html->adminOtherHead($this);
	echo $this->Html->niceBox();
?>
	<p>Infinitas has detected that your database is outdated.</p>

	<p><?php echo $this->Html->link('Update database', array('action' => 'update')); ?></p>
<?php echo $this->Html->niceBoxEnd();?>