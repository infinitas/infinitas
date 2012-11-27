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

    echo $this->Form->create('Menu', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'toggle',
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
			$this->Paginator->sort('name'),
			$this->Paginator->sort('type') => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('count') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('active') => array(
				'style' => 'width:50px;'
			)
		));

		foreach ($menus as $menu) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($menu); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(
							$menu['Menu']['name'],
							array(
								'controller' => 'menu_items',
								'action' => 'index',
								'menu_id' => $menu['Menu']['id']
							)
						);
					?>&nbsp;
				</td>
				<td><?php echo $menu['Menu']['type']; ?>&nbsp;</td>
				<td><?php echo $this->Design->count($menu['Menu']['item_count']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->status($menu['Menu']['active']); ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');