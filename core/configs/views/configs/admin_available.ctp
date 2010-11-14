<?php
    echo $this->Form->create('Config', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(array('add'));
		echo $this->Infinitas->adminIndexHead(null, $massActions);
?>
<div class="table">
	<h1 class="no-gap">
		<?php echo __('Overloaded Values', true); ?>
		<small><?php __('Click a link below to edit / delete the overladed value'); ?></small>
	</h1>
    <table class="listing no-gap" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    __('Key', true) => array(
                        'style' => 'width:200px;'
                    ),
                    __('Value', true) => array(
                        'style' => 'width:200px;'
                    )
                )
            );

            foreach ($overloaded as $key => $value){
				unset($configs[$key]);
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                		<td>
                			<?php
								echo $this->Html->link(
									$key,
									array(
										'action' => 'index',
										'Config.key' => $key
									)
								);
							?>&nbsp;
                		</td>
                		<td>
                			<?php echo $value; ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>

<div class="table">
	<h1 class="no-gap">
		<?php echo __('Default Values', true); ?>
		<small><?php __('Click a link below to overload the config with new values'); ?></small>
	</h1>
    <table class="listing no-gap" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    __('Key', true) => array(
                        'style' => 'width:200px;'
                    ),
                    __('Value', true) => array(
                        'style' => 'width:200px;'
                    )
                )
            );

            foreach ($configs as $key => $value){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                		<td>
                			<?php
								echo $this->Html->link(
									$key,
									array(
										'action' => 'add',
										'Config.key' => $key
									)
								);
							?>&nbsp;
                		</td>
                		<td>
                			<?php echo $value; ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>