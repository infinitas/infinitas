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
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   sort
	 * @subpackage	sort.comments
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.5a
	 */

	echo $this->Form->create('GlobalTag', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('identifier'),
			$this->Paginator->sort('name'),
			$this->Paginator->sort('keyname'),
			$this->Paginator->sort('weight'),
			$this->Paginator->sort('created')
		));

		foreach ($tags as $tag) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($tag); ?>&nbsp;</td>
				<td><?php echo $tag['GlobalTag']['identifier']; ?>&nbsp;</td>
				<td><?php echo $tag['GlobalTag']['name']; ?>&nbsp;</td>
				<td><?php echo $tag['GlobalTag']['keyname']; ?>&nbsp;</td>
				<td><?php echo $tag['GlobalTag']['weight']; ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($tag['GlobalTag']['created']); ?></td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');