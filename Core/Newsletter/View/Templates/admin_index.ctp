<?php
    /**
     * Newsletter Templates admin index
     *
     * this is the page for admins to view all the templates in the newsletter
     * plugin.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       newsletter
     * @subpackage    newsletter.views.templates.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */
?>
<?php
	echo $this->Form->create('Template', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
		'view',
		'export',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('name'),
			$this->Paginator->sort('created') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:100px;'
			),
			__('Status') => array(
				'class' => 'actions',
				'width' => '50px'
			)
		));

		foreach($templates as $template) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($template); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link($template['Template']['name'], array(
							'action' => 'edit',
							$template['Template']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->date($template['Template']['created']); ?></td>
				<td><?php echo $this->Infinitas->date($template['Template']['modified']); ?></td>
				<td><?php echo $this->Locked->display($template); ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation'); ?>