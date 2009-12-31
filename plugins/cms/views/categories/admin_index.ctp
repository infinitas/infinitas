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
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    echo $this->Cms->adminIndexHead( $this, $paginator );
?>
<div class="table">
    <?php echo $this->Cms->adminTableHeadImages(); ?>
    <?php echo $this->Form->create( 'Category', array( 'url' => array( 'controller' => 'posts', 'action' => 'mass', 'admin' => 'true' ) ) ); ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Cms->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort( 'title' ),
                    $this->Paginator->sort( 'Section', 'Section.title' ),
                    $this->Paginator->sort( 'Group', 'Group.name' ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'Items', 'content_count' ) => array(
                        'style' => 'width:35px;'
                    ),
                    $this->Paginator->sort( 'views' ) => array(
                        'style' => 'width:40px;'
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
            foreach ( $categories as $category )
            {
                ?>
                	<tr class="<?php echo $this->Cms->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $category['Category']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php echo $this->Html->link( $category['Category']['title'], array('action' => 'edit', $category['Category']['id'] ) ); ?>
                		</td>
                		<td>
                			<?php echo $this->Html->link( $category['Section']['title'], array('controller' => 'sections', 'action' => 'edit', $category['Section']['id'] ) ); ?>
                		</td>
                		<td>
                			<?php echo $category['Group']['name']; ?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $category['Category']['content_count']; ?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $category['Category']['views']; ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort( $category['Category']['modified'] ); ?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Cms->ordering(
                			        $category['Category']['id'],
                			        $category['Category']['ordering']
                			    );
                			?>
                		</td>
                		<td>
                			<?php
                			    echo $this->Status->toggle( $category['Category']['active'], $category['Category']['id'] ),
                    			    $this->Status->locked( $category, 'Category' );
                			?>
                		</td>
                		<td class="actions">
                			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $category['Category']['id'])); ?>
                			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $category['Category']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $category['Category']['id'])); ?>
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