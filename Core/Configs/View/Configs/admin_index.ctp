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

    echo $this->Form->create(false, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('key') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('value'),
			$this->Paginator->sort('options') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('type') => array(
				'style' => 'width:50px;'
			),
			$this->Paginator->sort('modifeid') => array(
				'style' => 'width:75px;'
			)
		));

		foreach ($configs as $config) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($config); ?>&nbsp;</td>
				<td><?php echo $this->Html->adminQuickLink($config['Config']); ?>&nbsp;</td>
				<td><?php echo $config['Config']['value']; ?>&nbsp;</td>
				<td>
					<?php
						if (!empty($config['Config']['options'])) {
							echo $this->Text->toList(explode(',', Inflector::humanize($config['Config']['options'])), 'or');
						}
					?>&nbsp;
				</td>
				<td><?php echo Inflector::humanize($config['Config']['type']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($config['Config']['modified']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');