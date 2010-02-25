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

    echo $this->Form->create( 'Pages', array( 'url' => array( 'controller' => 'pages', 'action' => 'mass', 'admin' => 'true' ) ) );

        $massActions = $this->Core->massActionButtons(
            array(
                'add',
                'edit',
                'delete'
            )
        );
        echo $this->Core->adminIndexHead( $this, null, null, $massActions );
?>
<?php if(!$writable):?>
	<div class="error-message">
		Please ensure that <?php echo $path?> is writable by the web server.
	</div>
<?php endif;?>
<div class="table">
    <?php echo $this->Core->adminTableHeadImages(); ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Core->adminTableHeader(
                array(
                    $this->Form->checkbox( 'all' ) => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $this->Paginator->sort( 'name' ),
                    $this->Paginator->sort( 'file_name' )
                )
            );

            foreach ( $pages as $page ):?>
				<tr class="<?php echo $this->Core->rowClass(); ?>">
					<td><?php echo $this->Form->checkbox( $page['Page']['file_name'] ); ?>&nbsp;</td>
					<td>
						<?php echo $this->Html->link( Inflector::humanize($page['Page']['name']), array('controller' => 'pages', 'action' => 'edit', $page['Page']['file_name'])); ?>&nbsp;
					</td>
					<td>
						<?php echo $page['Page']['file_name']; ?>&nbsp;
					</td>
				</tr>
            <?php endforeach;?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>