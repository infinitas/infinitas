<?php
	/**
	 * Manage the routes for the sites
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   Infinitas.routes
	 * @subpackage	Infinitas.routes.views.admin_index
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.5a
	 */

echo $this->Form->create('Route', array('action' => 'mass'));
echo $this->Infinitas->adminIndexHead($filterOptions, array(
	'add',
	'edit',
	'copy',
	'move',
	'toggle',
	'delete'
));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('name'),
			__('Url', true ),
			__('Route', true ),
			__('Theme', true ) => array(
				'style' => 'width:95px;'
			),
			__( 'Order', true ) => array(
				'style' => 'width:50px;'
			),
			__( 'Active', true ) => array(
				'style' => 'width:50px;'
			)
		));

		foreach ($routes as $route) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($route); ?>&nbsp;</td>
				<td><?php echo $this->Html->adminQuickLink($route['Route']); ?>&nbsp;</td>
				<td><?php echo $route['Route']['url']; ?>&nbsp;</td>
				<td>
					<?php
						$prefix = ($route['Route']['prefix'])		 ? $route['Route']['prefix'] . '/'	 : '';
						$plugin = ($route['Route']['plugin'])		 ? $route['Route']['plugin'] . '/'	 : '';
						$controller = ($route['Route']['controller']) ? $route['Route']['controller'] . '/' : '';
						$action = ($route['Route']['action'])		 ? $route['Route']['action'] . '/'	 : 'index/';

						echo '/' . $prefix . $plugin . $controller . $action;
					?>&nbsp;
				</td>
				<td>
					<?php
						$route['Theme']['name'] = !empty($route['Theme']['name'])
							? $route['Theme']['name']
							: 'default';

						$route['Route']['layout'] = !empty($route['Route']['layout'])
							? $route['Route']['layout']
							: $route['Theme']['default_layout'];

						$route['Route']['layout'] = !empty($route['Route']['layout'])
							? $route['Route']['layout']
							: 'front';

						echo sprintf('%s - %s', Inflector::humanize($route['Theme']['name']), Inflector::humanize($route['Route']['layout']));
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->ordering($route['Route']['id'], $route['Route']['ordering']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->status($route['Route']['active']); ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');