<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    echo $this->Form->create('Lock', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead(null, array(
		'unlock'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('User.username', __d('locks', 'User')),
			$this->Paginator->sort('class'),
			$this->Paginator->sort('foreign_key'),
			$this->Paginator->sort('created'),
		));

		foreach ($locks as $lock) {
			?>
				<tr>
					<td><?php echo $this->Infinitas->massActionCheckBox($lock); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link(
								$lock['Locker']['username'],
								array(
									'plugin' => 'users',
									'controller' => 'users',
									'action' => 'edit',
									$lock['Locker']['id']
								)
							);
						?>&nbsp;
					</td>
					<td><?php echo $lock['Lock']['class']; ?>&nbsp;</td>
					<td><?php echo $lock['Lock']['foreign_key']; ?>&nbsp;</td>
					<td><?php echo $this->Time->timeAgoInWords($lock['Lock']['created']); ?>&nbsp;</td>
				</tr>
			<?php
		}
	?>
</table>
<?php
	echo $this->Html->tag('div', $this->Form->checkbox('Lock.0.id', array('checked' => true)), array(
		'class' => 'hide'
	));
	echo $this->Form->end();