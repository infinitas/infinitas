<?php
	/**
	 * Static Page Admin index
	 *
	 * Creating and maintainig static pages
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.views.admin_index
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dakota
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

    echo $this->Form->create( 'Page', array( 'url' => array( 'controller' => 'pages', 'action' => 'mass', 'admin' => 'true' ) ) );

	$massActions = $this->Core->massActionButtons(
		array(
			'add',
			'edit',
			'delete'
		)
	);

	echo $this->Core->adminIndexHead( $this, $paginator, $filterOptions, $massActions );

	if(!$writable){ ?>
		<div class="error-message">
			<?php sprintf(__('Please ensure that %s is writable by the web server.', true), $path); ?>
		</div><?php
	}
?>
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

            $id = 0;
			foreach ( $pages as $page ){ ?>
				<tr class="<?php echo $this->Core->rowClass(); ?>">
					<td><?php echo $this->Form->checkbox( 'Page.'.$id.'.id', array('value' => $page['Page']['file_name']) ); ?>&nbsp;</td>
					<td>
						<?php echo $this->Html->link( Inflector::humanize($page['Page']['name']), array('controller' => 'pages', 'action' => 'edit', $page['Page']['file_name'])); ?>&nbsp;
					</td>
					<td>
						<?php echo $page['Page']['file_name']; ?>&nbsp;
					</td>
				</tr><?php
				$id++;
			}
		?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element( 'pagination/navigation' ); ?>