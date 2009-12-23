<?php
    echo $this->Cms->adminIndexHead( $this, $paginator );
?>
<div class="table">
    <?php echo $this->Cms->adminTableHeadImages(); ?>
    <?php echo $this->Form->create( 'Section', array( 'url' => array( 'controller' => 'posts', 'action' => 'mass', 'admin' => 'true' ) ) ); ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Cms->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'width' => '25px'
                    ),
                    $this->Paginator->sort( 'title' ),
                    $this->Paginator->sort( __( 'Group', true ), 'Group.name' ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( __( 'Categories', true ), 'category_count' ) => array(
                        'style' => 'width:60px;'
                    ),
                    $this->Paginator->sort( 'views' ) => array(
                        'style' => 'width:35px;'
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
            foreach ( $sections as $section )
            {
                ?>
                	<tr class="<?php echo $this->Cms->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $section['Section']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( $section['Section']['title'], array( 'action' => 'edit', $section['Section']['id'] ) ); ?>
                		</td>
                		<td>
                			<?php echo $section['Group']['name']; ?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $section['Section']['category_count']; ?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $section['Section']['views']; ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort( $section['Section']['modified'] ); ?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Cms->ordering(
                			        $section['Section']['id'],
                			        $section['Section']['ordering']
                			    );
                			?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Status->toggle( $section['Section']['active'], $section['Section']['id'] ),
                    			    $this->Status->locked( $section, 'Section' );
                			?>
                		</td>

                		<td class="actions">
                			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $section['Section']['id'])); ?>
                			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $section['Section']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $section['Section']['id'])); ?>
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