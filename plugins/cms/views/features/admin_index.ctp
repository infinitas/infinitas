<?php
    echo $this->Cms->adminIndexHead( $this, $paginator, $filterOptions );
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
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

?>
<div class="table">
    <?php echo $this->Cms->adminTableHeadImages(); ?>
    <?php echo $this->Form->create( 'Feature', array( 'url' => array( 'controller' => 'features', 'action' => 'mass', 'admin' => 'true' ) ) ); ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Cms->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort( 'Content Item', 'Content.title' ),
                    __( 'Category', true ),
                    $this->Paginator->sort( 'created' ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'modified' ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'ordering' ) => array(
                        'style' => 'width:50px;'
                    ),
                    __( 'Status', true ) => array(
                        'style' => 'width:50px;'
                    ),
                    __( 'Actions', true ) => array(
                        'style' => 'width:100px;'
                    )
                )
            );

            $i = 0;
            foreach ( $features as $feature )
            {
                ?>
                	<tr class="<?php echo $this->Cms->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $feature['Feature']['content_id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( $feature['Content']['title'], array('controller' => 'features', 'action' => 'view', $feature['Content']['id'])); ?>
                		</td>
                		<td>
                			<?php echo $this->Html->link( $feature['Content']['Category']['title'], array( 'controller' => 'features', 'action' => 'edit', $feature['Content']['Category']['id'] ) ); ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort( $feature['Feature']['created'] ); ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort( $feature['Feature']['modified'] ); ?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Cms->ordering(
                			        $feature['Feature']['content_id'],
                			        $feature['Feature']['ordering']
                			    );
                			?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Status->toggle( $feature['Content']['active'], $feature['Content']['id'], array( 'controller' => 'features', 'action' => 'toggle' ) );
                			?>
                		</td>
                		<td class="actions">
                			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $feature['Feature']['content_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $feature['Feature']['content_id'])); ?>
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php
        echo $this->Form->button( __( 'Delete', true ), array( 'value' => 'delete', 'name' => 'delete' ) );
        echo $this->Form->button( __( 'Toggle', true ), array( 'value' => 'toggle' ) );
        echo $this->Form->end();

    ?>
</div>
<?php echo $this->element( 'pagination/navigation' ); ?>