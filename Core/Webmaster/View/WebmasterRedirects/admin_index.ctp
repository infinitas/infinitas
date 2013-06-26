<?php
/**
 * @brief Add some documentation for this admin_index form.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/Webmaster
 * @package	   Webmaster.View.admin_index
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

echo $this->Form->create(null, array('action' => 'mass'));
echo $this->Infinitas->adminIndexHead($filterOptions, array(
	'add',
	'edit',
	'toggle',
	'ignore',
	'copy',
	'delete'
));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(
			array(
				$this->Form->checkbox('all') => array(
					'class' => 'first',
				),
				$this->Paginator->sort('url', __d('webmaster', 'From')),
				$this->Paginator->sort('redirect_to', __d('webmaster', 'To')),
				$this->Paginator->sort('redirect_permanent', __d('webmaster', 'Status code')) => array(
					'class' => 'large'
				),
				$this->Paginator->sort('error_count', __d('webmaster', 'Errors')) => array(
					'class' => 'small'
				),
				$this->Paginator->sort('redirect_count', __d('webmaster', 'Redirects')) => array(
					'class' => 'small'
				),
				$this->Paginator->sort('modified', __d('webmaster', 'Updated')) => array(
					'class' => 'date'
				),
			)
		);

		foreach ($webmasterRedirects as $webmasterRedirect) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($webmasterRedirect); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->adminQuickLink($webmasterRedirect['WebmasterRedirect']); ?>&nbsp;</td>
				<td><?php echo $webmasterRedirect['WebmasterRedirect']['redirect_to']; ?>&nbsp;</td>
				<td><?php echo $webmasterRedirect['WebmasterRedirect']['redirect_permanent'] ? 301 : 301; ?>&nbsp;</td>
				<td><?php echo $this->Design->count($webmasterRedirect['WebmasterRedirect']['error_count']); ?>&nbsp;</td>
				<td><?php echo $this->Design->count($webmasterRedirect['WebmasterRedirect']['redirect_count']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($webmasterRedirect['WebmasterRedirect']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');