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

	echo $this->Form->create(false, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'install',
		'edit',
		'toggle',
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
			$this->Paginator->sort('default_layout'),
			$this->Paginator->sort('licence') => array(
				'class' => 'xlarge'
			),
			$this->Paginator->sort('author') => array(
				'class' => 'large'
			),
			$this->Paginator->sort('core') => array(
				'class' => 'small'
			),
			$this->Paginator->sort('active') => array(
				'class' => 'small'
			)
		));

		foreach($themes as $theme) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($theme); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(Inflector::humanize($theme['Theme']['name']), array(
							'action' => 'edit',
							$theme['Theme']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo Inflector::humanize($theme['Theme']['default_layout']); ?>&nbsp;</td>
				<td>
					<?php
						if($theme['Theme']['licence']) {
							echo $theme['Theme']['licence'];
						} else {
							echo sprintf('&copy; %s', $theme['Theme']['author']);
						}
					?>&nbsp;
				</td>
				<td>
					<?php
						$auth = $theme['Theme']['author'];
						if (!empty($theme['Theme']['url'])) {
							$auth = $this->Html->link($theme['Theme']['author'], $theme['Theme']['url'], array('target' => '_blank'));
						}

						echo $auth;
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->status($theme['Theme']['core']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->status($theme['Theme']['active']); ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');