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
?>
<div class="install form">
	<blockquote class="extract">
	    <p><?php 
				echo __('Infinitas is now ready to install onto your server.', true );
				echo __(', .');
			?>
		</p>
	</blockquote>
	<br />
	<?php
		echo $this->Form->input(
			'Sample.sample', 
			array(
				'type' => 'radio',
				'options' => array(1 => 'Yes', 0 => 'No'),
				'value' => 0,
				'legend' => 'Do you wish to install some sample data (Which is recommended for new Infinitas users)?',
			)
		);
	?>
</div>
	<h4 class="field-heading"><?php echo sprintf(__('Plugins included for installation (%d)'), count($plugins)); ?></h4>
	<table width="100%">
		<thead>
			<th>Plugin</th>
			<th>Version</th>
			<th>Author</th>
			<th>License</th>
		</thead>
		<tbody>
			<?php 
				foreach($plugins as $plugin => $data) {
					?>
						<tr>
							<td title="<?php echo $data['description']; ?>"><?php echo Inflector::humanize($data['name']); ?>&nbsp;</td>
							<td><?php echo $data['version']; ?>&nbsp;</td>
							<td><?php echo $data['author']; ?>&nbsp;</td>
							<td><?php echo $data['license']; ?>&nbsp;</td>
						</tr>
					<?php
				}
			?>
		</tbody>
	</table>