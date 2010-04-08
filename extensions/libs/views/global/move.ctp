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
    echo $this->Form->create($model, array('url' => '/'.$this->params['url']['url']));
    $massActions = $this->Infinitas->massActionButtons(
		array(
			'move'
		)
	);
	echo $this->Infinitas->adminIndexHead($this, null, null, $massActions);
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
        	$belongs = $habtm = array();
			foreach($relations['belongsTo'] as $belongsTo){
				$belongs[] = __(prettyName($belongsTo['className']), true);
			}

			foreach($relations['hasAndBelongsToMany'] as $hasAndBelongsToMany){
				$habtm[] = __(prettyName($hasAndBelongsToMany['className']), true);
			}

            echo $this->Infinitas->adminTableHeader(
                array(
                    '' => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    __('Record', true) => array(
                        'style' => 'width:150px;'
                    )
                ) + $belongs + $habtm
            );

            $i = 0;
            foreach($rows as $row){
                ?>
                    <tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox($model.'.'.$row[$model]['id']); ?>&nbsp;</td>
                        <td>
                            <?php echo $row[$model]['name']; ?>
                        </td>
		                <?php
				            foreach($relations['belongsTo'] as $belongsTo){
				            	if(isset(${strtolower(Inflector::pluralize($belongsTo['className']))}[$row[$model][$belongsTo['foreignKey']]])){
				            		echo '<td>'.${strtolower(Inflector::pluralize($belongsTo['className']))}[$row[$model][$belongsTo['foreignKey']]].'</td>';
				            	}
				            	else{
				            		echo '<td>'.__('Not set yet', true).'</td>';
				            	}
							}

							foreach($relations['hasAndBelongsToMany'] as $hasAndBelongsToMany){
								echo '<td>todo</td>';
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
	echo $this->Form->hidden('Move.referer', array('value' => $referer));

    foreach($relations['belongsTo'] as $alias => $belongsTo){
    	?><div class="info"><?php
	    	echo $this->Design->niceBox(); ?>
	        	<h3><?php echo __(prettyName($alias), true); ?></h3><?php
	        	echo $this->Form->input('Move.'.$belongsTo['foreignKey'], array('label' => false, 'empty' => __(Configure::read('Website.empty_select'), true)));
	        echo $this->Design->niceBoxEnd();
        ?></div><?php
    }

    echo $this->Form->end();
?>