<?php
    /**
     * global content
	 *
	 * Almost everything has a title and a description of some sort, so this
	 * makes things a bit simpler. Here you can do some basic management.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       Infinitas.contents
     * @subpackage    Infinitas.contents.views.global_content.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.8a
     */

    echo $this->Form->create('GlobalContent', array('action' => 'mass'));
		echo $this->Infinitas->adminIndexHead($filterOptions, array(
			'edit',
			'move',
			'delete'
		));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('title'),
			$this->Paginator->sort('model'),
			$this->Paginator->sort('Layout.name', __d('contents', 'Layout')) => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('ContentGroup.name', __d('contents', 'Group')) => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('keyword_density'),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:100px;'
			)
		));

		foreach ($contents as $content) {?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($content); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->adminQuickLink($content['GlobalContent']); ?>&nbsp;</td>
				<td><?php echo $content['GlobalContent']['model']; ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->adminQuickLink(
							$content['Layout'],
							array('controller' => 'global_layouts'),
							'GlobalLayout'
						);
					?>&nbsp;
				</td>
				<td><?php echo $content['ContentGroup']['name'] ? $content['ContentGroup']['name'] : __d('contents', 'Public'); ?>&nbsp;</td>
				<td><?php echo $content['GlobalContent']['keyword_density']; ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($content['GlobalContent']['modified']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');