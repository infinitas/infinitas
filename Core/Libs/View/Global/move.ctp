<?php
	/**
	 * Core moving records view
	 *
	 * display a page with a list of records that are being moved and the
	 * places they can be moved to.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.views.global.move
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	echo $this->Form->create($model);
	echo $this->Infinitas->adminIndexHead(null, array(
		'move',
		'cancel'
	));
?>
<table class="listing">
	<?php
		$ignore = array(
			'Locker'
		);

		$belongs = $habtm = array();
		foreach ($relations['belongsTo'] as $alias => $belongsTo) {
			if (in_array($alias, $ignore)) {
				continue;
			}
			$belongs[] = __d('libs', prettyName($alias));
		}

		foreach ($relations['hasAndBelongsToMany'] as $alias => $hasAndBelongsToMany) {
			if (in_array($alias, $ignore)) {
				continue;
			}
			$habtm[] = __d('libs', prettyName(Inflector::pluralize($alias)));
		}

		echo $this->Infinitas->adminTableHeader(array_merge(array(
			'' => array(
				'class' => 'first'
			),
			__d('libs', 'Record') => array(
				'style' => 'width:150px;'
			)
		), $belongs, $habtm));

		foreach ($rows as $row) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($row); ?>&nbsp;</td>
				<td>
					<?php echo $row[$model][$modelSetup['displayField']]; ?>
				</td>
				<?php
					foreach ($relations['belongsTo'] as $alias => $belongsTo) {
						if (in_array($alias, $ignore)) {
							continue;
						}

						if (isset(${strtolower(Inflector::pluralize($alias))}[$row[$model][$belongsTo['foreignKey']]])) {
							echo $this->Html->tag('td', ${strtolower(Inflector::pluralize($alias))}[$row[$model][$belongsTo['foreignKey']]]);
						} else {
							echo $this->Html->tag('td', __d('infinitas', 'Not set'));
						}
					}

					foreach ($relations['hasAndBelongsToMany'] as $alias => $hasAndBelongsToMany) {
						echo $this->Html->tag('td', __d('infinitas', 'Some tags'));
					}
				?>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->hidden('Move.model', array('value' => $model));
	echo $this->Form->hidden('Move.confirmed', array('value' => 1));

	foreach ($relations['belongsTo'] as $alias => $belongsTo) {
		if (in_array($alias, $ignore)) {
			continue;
		}

		echo $this->Html->tag('div', implode('', array(
			$this->Html->tag('h3', __d('libs', prettyName($alias))),
			$this->Form->input('Move.'.$belongsTo['foreignKey'], array(
				'label' => false,
				'empty' => __d('libs', Configure::read('Website.empty_select'))
			))
		)), array('class' => 'info'));
	}

	foreach ($relations['hasAndBelongsToMany'] as $alias => $belongsTo) {
		echo $this->Html->tag('div', implode('', array(
			$this->Html->tag('h3', __d('libs', prettyName(Inflector::pluralize($alias)))),
			$this->Form->input('Move.'.$alias, array(
				'label' => false,
				'multiple' => 'multiple',
				'options' => ${strtolower($alias)}
			))
		)), array('class' => 'info'));
	}

echo $this->Form->end();