<?php
	/**
	 * Core delete confirmation.
	 *
	 * show a confirm page for delete when there is no javascript
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package core
	 * @subpackage core.views.global.delete
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

    echo $this->Form->create( $model, array('url' => '/' . $this->params['url']['url']) );
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'delete',
                'cancel'
            )
        );
	echo $this->Infinitas->adminIndexHead(null, $massActions);
?>
	<div class="table">
		<table class="listing" cellpadding="0" cellspacing="0">
			<?php
				echo $this->Infinitas->adminTableHeader(
					array(
						$this->Form->checkbox('all') => array(
							'class' => 'first',
							'style' => 'width:25px;'
						),
						__('Record', true) => array(
							'style' => 'width:150px;'
						)
					)
				);

				$i = 0;
				foreach($rows as $key => $value){
					?>
						<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
							<td><?php echo $this->Form->checkbox($key); ?>&nbsp;</td>
							<td>
								<?php echo $value; ?>
							</td>
						</tr>
					<?php
					echo $this->Form->hidden('Confirm.model', array('value' => $model));
					echo $this->Form->hidden('Confirm.confirmed', array('value' => 1));
					echo $this->Form->hidden('Confirm.referer', array('value' => $referer));
					$i++;
				}
			?>
		</table>
		<?php echo $this->Form->end(); ?>
	</div>