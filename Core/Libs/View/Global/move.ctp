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
	echo $this->Form->create($model, array('url' => '/' . $this->request->url));

	$massActions = $this->Infinitas->massActionButtons(
		array(
			'move'
		)
	);

	echo $this->Infinitas->adminIndexHead(null, $massActions);
?>
<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
		<?php
			$ignore = array(
				'Locker'
			);

			$belongs = $habtm = array();
			foreach($relations['belongsTo'] as $alias => $belongsTo) {
				if(in_array($alias, $ignore)) {
					continue;
				}
				$belongs[] = __(prettyName($alias));
			}

			foreach($relations['hasAndBelongsToMany'] as $alias => $hasAndBelongsToMany) {
				if(in_array($alias, $ignore)) {
					continue;
				}
				$habtm[] = __(prettyName(Inflector::pluralize($alias)));
			}

			echo $this->Infinitas->adminTableHeader(
				array_merge(
					array(
						'' => array(
							'class' => 'first',
							'style' => 'width:25px;'
						),
						__('Record') => array(
							'style' => 'width:150px;'
						)
					),
					$belongs,
					$habtm
				)
			);

			$i = 0;
			foreach($rows as $row) {
				?>
					<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
						<td><?php echo $this->Infinitas->massActionCheckBox($row); ?>&nbsp;</td>
						<td>
							<?php echo $row[$model][$modelSetup['displayField']]; ?>
						</td>
						<?php
							foreach($relations['belongsTo'] as $alias => $belongsTo) {
								if(in_array($alias, $ignore)) {
									continue;
								}

								if(isset(${strtolower(Inflector::pluralize($alias))}[$row[$model][$belongsTo['foreignKey']]])) {
									echo '<td>'.${strtolower(Inflector::pluralize($alias))}[$row[$model][$belongsTo['foreignKey']]].'</td>';
								}
								else{
									echo '<td>'.__('Not set').'</td>';
								}
							}

							foreach($relations['hasAndBelongsToMany'] as $alias => $hasAndBelongsToMany) {
								echo '<td>'.__('Some tags').'</td>';
							}

							$i++;
						?>
					</tr>
			   	<?php
			}
		?>
	</table>
</div>
<?php
	echo $this->Form->hidden('Move.model', array('value' => $model));
	echo $this->Form->hidden('Move.confirmed', array('value' => 1));

	foreach($relations['belongsTo'] as $alias => $belongsTo) {
		if(in_array($alias, $ignore)) {
			continue;
		}
		?><div class="info">
			<h3><?php echo __(prettyName($alias)); ?></h3><?php
			echo $this->Form->input('Move.'.$belongsTo['foreignKey'], array('label' => false, 'empty' => __(Configure::read('Website.empty_select'))));
		?></div><?php
	}

	foreach($relations['hasAndBelongsToMany'] as $alias => $belongsTo) {
		?><div class="info">
			<h3><?php echo __(prettyName(Inflector::pluralize($alias))); ?></h3><?php
			echo $this->Form->input('Move.'.$alias, array('label' => false, 'multiple' => 'multiple', 'options' => ${strtolower($alias)}));
		?></div><?php
	}

	echo $this->Form->end();
?>