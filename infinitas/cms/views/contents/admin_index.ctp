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

    echo $this->Form->create( 'Content', array( 'url' => array( 'controller' => 'contents', 'action' => 'mass', 'admin' => 'true' ) ) );
        $massActions = $this->Cms->massActionButtons(
            array(
                'add',
                'edit',
                'preview',
                'toggle',
                'copy',
                'move',
                'delete'
            )
        );
        echo $this->Cms->adminIndexHead( $this, $paginator, $filterOptions, $massActions );
?>
<div class="table">
    <table class ="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Cms->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort( 'title' ),
                    $this->Paginator->sort( 'Category', 'Category.title' ),
                    $this->Paginator->sort( 'Group', 'Group.name' ) => array(
                        'style' => 'width:100px;'
                    ),
                    $this->Paginator->sort( 'Layout', 'Layout.name' ) => array(
                        'style' => 'width:100px;'
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
                        'style' => 'width:100px;'
                    )
                )
            );

            $i = 0;
            foreach ( $contents as $content )
            {
                ?>
                	<tr class="<?php echo $this->Cms->rowClass(); ?>">
                        <td><?php echo $this->Form->checkbox( $content['Content']['id'] ); ?>&nbsp;</td>
                		<td>
                			<?php
                			    echo $this->Html->link(
                			        $content['Content']['title'],
                			        array(
                    			        'controller' => 'contents',
                    			        'action' => 'edit',
                    			        $content['Content']['id']
                    			    )
                    			);
                			?>
                		</td>
                		<td>
                			<?php
								if(isset($content['Category']['title'])) {
									echo $this->Html->link(
										$content['Category']['title'],
										array(
											'plugin' => 'management',
											'controller' => 'categories',
											'action' => 'edit',
											$content['Category']['id']
										)
									);
								}
                        	?>
                		</td>
                		<td>
                			<?php echo $content['Group']['name']; ?>
                		</td>
                		<td>
                			<?php echo $content['Layout']['name']; ?>
                		</td>
                		<td style="text-align:center;">
                			<?php echo $content['Content']['views']; ?>
                		</td>
                		<td>
                			<?php echo $this->Time->niceShort( $content['Content']['modified'] ); ?>
                		</td>
                		<td class="status">
                			<?php echo $this->Cms->ordering($content['Content']['id'], $content['Content']['ordering'], 'Cms.Content'); ?>&nbsp;
                		</td>
                		<td class="status">
                			<?php
                			    echo $this->Cms->homePageItem( $content ),
                        			$this->Infinitas->featured( $content ),
                			        $this->Infinitas->status( $content['Content']['active'], $content['Content']['id'] ),
                    			    $this->Infinitas->locked( $content, 'Content' );
                			?>
                		</td>
                	</tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'admin/pagination/navigation' ); ?>