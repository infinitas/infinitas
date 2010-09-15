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
    <h2><?php __( 'Database Installation' ); ?></h2>
	<blockquote class="extract">
	    <p><?php echo __( 'You can review which plugins will be installed and optionally install some sample data.', true ); ?></p>
	</blockquote>
	<h3><?php __('Sample data') ?> </h3>
		<p><?php echo __('Check the checkbox below if you wish to install some sample data. The sample data can be useful to give you an overview of the capabilities of the plugins.'); ?> </p>
		<?php
			echo $this->Form->input('sample', array(
				'type' => 'checkbox',
				'label' => 'Sample data',
			))
		?>
	<h3><?php __('Core plugins') ?></h3>
	<?php echo $this->element('plugin_list', array('plugins' => $availablePlugins['core'])); ?>

	<?php
		if(!empty($availablePlugins['plugin'])) {
			echo '<h3>' . __('Plugins', true) . '</h3>';
			echo $this->element('plugin_list', array('plugins' => $availablePlugins['plugin']));
		}
	?>
</div>