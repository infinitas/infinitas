<?php
/**
 * Theme listing
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Theme.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Form->create(false, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'install',
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
			$this->Paginator->sort('name'),
			$this->Paginator->sort('default_layout', __d('themes', 'Layout')) => array(
				'title' => __d('themes', 'The default layout that will be used when this theme is active')
			),
			__d('themes', 'Layouts') => array(
				'class' => 'small'
			),
			__d('installer', 'Status'),
			$this->Paginator->sort('licence') => array(
				'class' => 'xlarge'
			),
			$this->Paginator->sort('author') => array(
				'class' => 'large'
			),
		));

		foreach ($themes as $theme) { ?>
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
				<td>
					<?php
						$theme['Theme']['default_layout'] = trim(Inflector::humanize($theme['Theme']['default_layout']));
						if (empty($theme['Theme']['default_layout'])) {
							$theme['Theme']['default_layout'] = Inflector::humanize(Configure::read('Themes.default_layout')) . ' ' .
								$this->Html->tag('span', __d('installer', 'Default'), array(
									'class' => 'label label-info'
								));
						}
						echo $theme['Theme']['default_layout'];
					?>&nbsp;
				</td>
				<td>
					<?php
						echo $this->Html->tag('span', InfinitasTheme::layoutCount($theme['Theme']['name']), array(
							'class' => 'badge badge-success'
						));
					?>&nbsp;
				</td>
				<td>
					<?php
						if ($theme['Theme']['admin']) {
							echo $this->Html->tag('span', __d('themes', 'Admin'), array(
								'class' => 'label label-inverse'
							));
						}
						if ($theme['Theme']['frontend']) {
							echo $this->Html->tag('span', __d('themes', 'Frontend'), array(
								'class' => 'label label-info'
							));
						}

						if (!$theme['Theme']['admin'] && !$theme['Theme']['frontend']) {
							echo $this->Html->tag('span', __d('themes', 'Frontend'), array(
								'class' => 'label'
							));
						}
					?>&nbsp;
				</td>
				<td>
					<?php
						if ($theme['Theme']['licence']) {
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
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');