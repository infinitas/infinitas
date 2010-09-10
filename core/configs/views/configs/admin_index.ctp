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

    echo $this->Form->create('Config', array('url' => array('action' => 'mass')));

        $massActions = $this->Infinitas->massActionButtons(
            array(
                'add',
                'edit',
                'copy', // @todo -c Implement .should read the file populate $this->data and render add
            )
        );
        echo $this->Infinitas->adminIndexHead( $this, $filterOptions, $massActions );
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
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
                    __('Core', true) => array(
                        'style' => 'width:50px;'
                    )
                )
            );

            foreach ($configs as $config){
                ?>
                	<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $config['Config']['id'] ); ?>&nbsp;</td>
                		<td title="<?php echo __('Description', true), ' :: ', $this->Text->Truncate($config['Config']['description'], 200, array('html' => true)); ?>">
                			<?php echo $this->Html->link( $config['Config']['key'], array('controller' => 'configs', 'action' => 'edit', $config['Config']['id'])); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $config['Config']['value']; ?>&nbsp;
                		</td>
                		<td>
                			<?php
                			    if (!empty($config['Config']['options'])){
                			        echo $this->Text->toList(explode(',', Inflector::humanize($config['Config']['options'])), 'or');
                			    }
                			?>&nbsp;
                		</td>
                		<td>
                			<?php echo Inflector::humanize($config['Config']['type']); ?>&nbsp;
                		</td>
                		<td>
                			<?php echo $this->Infinitas->status($config['Config']['core']); ?>&nbsp;
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>