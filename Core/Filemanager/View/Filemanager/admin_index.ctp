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

echo $this->Form->create('FileManager', array('url' => array('action' => 'mass')));
echo $this->Infinitas->adminIndexHead(null, array(
	'upload',
	'view',
	'edit',
	'copy',
	'delete'
));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first',
			),
			__d('filemanager', 'Type') => array(
				'class' => 'small'
			),
			__d('filemanager', 'Name'),
			__d('filemanager', 'Path'),
			__d('filemanager', 'Size') => array(
				'style' => 'width:75px;'
			),
			__d('filemanager', 'Owner / Group') => array(
				'style' => 'width:60px;'
			),
			__d('filemanager', 'Folders / Files') => array(
				'style' => 'width:60px;'
			),
			__d('filemanager', 'Chmod / Octal') => array(
				'style' => 'width:100px;'
			),
			__d('filemanager', 'Created') => array(
				'style' => 'width:100px;'
			),
			__d('filemanager', 'Modified') => array(
				'style' => 'width:100px;'
			),
			__d('filemanager', 'Accessed') => array(
				'style' => 'width:100px;'
			)
		)); ?>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo $this->Image->image( 'actions', 'arrow-left' ); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr> <?php

		foreach ($folders as $folder) { ?>
			<tr>
				<td><?php echo $this->Form->checkbox('Folder.'.$folder['Folder']['path']); ?>&nbsp;</td>
				<td><?php echo $this->Image->findByExtention(); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(
							$folder['Folder']['name'],
							array(
								'action' => 'index',
							) + array_merge((array)$this->request->params['pass'], (array)$folder['Folder']['name'])
						);
					?>&nbsp;
				</td>
				<td><?php echo str_replace(array(APP, '//'), array('APP', '/'), $folder['Folder']['path']); ?>&nbsp;</td>
				<td><?php echo $this->Number->toReadableSize($folder['Folder']['size']); ?>&nbsp;</td>
				<td><?php echo $folder['Folder']['owner'].' / '.$folder['Folder']['group']; ?>&nbsp;</td>
				<td><?php echo $folder['Folder']['sub_folders'].' / '.$folder['Folder']['sub_files']; ?>&nbsp;</td>
				<td><?php echo $folder['Folder']['permission'].' / '.$folder['Folder']['octal']; ?></td>
				<td><?php echo $this->Infinitas->date($folder['Folder']['created']); ?></td>
				<td><?php echo $this->Infinitas->date($folder['Folder']['modified']); ?></td>
				<td><?php echo $this->Infinitas->date($folder['Folder']['accessed']); ?></td>
			</tr><?php
		}

		foreach ($files as $file) { ?>
			<tr>
				<td><?php echo $this->Form->checkbox('File.'.$file['File']['path']); ?>&nbsp;</td>
				<td><?php echo $this->Image->findByExtention($file['File']['extension']); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(
							$file['File']['name'],
							array(
								'action' => 'view',
							) + array_merge((array)$this->request->params['pass'], (array)$file['File']['name'])
						);
					?>&nbsp;
				</td>
				<td><?php echo $file['File']['path']; ?>&nbsp;</td>
				<td><?php echo $this->Number->toReadableSize($file['File']['size']); ?>&nbsp;</td>
				<td><?php echo $file['File']['owner'].' / '.$file['File']['group']; ?>&nbsp;</td>
				<td>&nbsp;</td>
				<td><?php echo $file['File']['permission'].' / '.$file['File']['octal']; ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($file['File']['created']); ?></td>
				<td><?php echo $this->Infinitas->date($file['File']['modified']); ?></td>
				<td><?php echo $this->Infinitas->date($file['File']['accessed']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();