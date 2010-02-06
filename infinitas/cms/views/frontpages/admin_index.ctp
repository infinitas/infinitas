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

    echo $this->Form->create( 'Frontpage', array( 'url' => array( 'controller' => 'frontpages', 'action' => 'mass', 'admin' => 'true' ) ) );
        $massActions = $this->Cms->massActionButtons(
            array(
                'add',
                'delete'
            )
        );
        echo $this->Cms->adminIndexHead( $this, $paginator, $filterOptions, $massActions );

?>
<div class="table">
    <?php echo $this->Cms->adminTableHeadImages(); ?>
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
                    )
                )
            );

            $i = 0;
            foreach ( $frontpages as $frontpage )
            {
                ?>
                	<tr class="<?php echo $this->Cms->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $frontpage['Frontpage']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( $frontpage['Content']['title'], array('controller' => 'contents', 'action' => 'view', $frontpage['Content']['id'])); ?>
                		</td>
                		<td>
                			<?php echo $this->Html->link( $frontpage['Content']['Category']['title'], array( 'controller' => 'categories', 'action' => 'edit', $frontpage['Content']['Category']['id'] ) ); ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort( $frontpage['Frontpage']['created'] ); ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort( $frontpage['Frontpage']['modified'] ); ?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Cms->ordering(
                			        $frontpage['Frontpage']['content_id'],
                			        $frontpage['Frontpage']['ordering']
                			    );
                			?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Infinitas->toggle( $frontpage['Content']['active'], $frontpage['Content']['id'], array( 'controller' => 'contents', 'action' => 'toggle' ) );
                			?>
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php
        echo $this->Form->end();

    ?>
</div>
<?php echo $this->element( 'pagination/navigation' ); ?>