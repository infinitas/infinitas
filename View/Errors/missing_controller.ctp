<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$pluginNotInstalled = false;
$possiblePlugin = array_filter(explode('/', $this->request->here));
if(empty($plugin) && !empty($possiblePlugin)) {
	$possiblePlugin = Inflector::camelize(current($possiblePlugin));
	if(in_array($possiblePlugin, App::objects('plugin')) && !InfinitasPlugin::loaded($possiblePlugin)) {
		$plugin = $possiblePlugin;
		$pluginNotInstalled = true;
	}
}

$pluginDot = empty($plugin) ? null : $plugin . '.';

?>
<h2><?php echo __d('cake_dev', $pluginNotInstalled ? 'Uninstalled plugin' : 'Missing Controller'); ?></h2>
<p class="error">
	<strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
	<?php 
		if($pluginNotInstalled) {
			echo __d('cake_dev', '<em>%s</em> has not been installed.', $pluginDot . $class); 
		}
		else {
			echo __d('cake_dev', '<em>%s</em> could not be found.', $pluginDot . $class); 
		}
	?>
</p>
<p class="error">
	<strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
	<?php 
		if($pluginNotInstalled) {
			echo __d(
				'cake_dev', 
				'Run <em>cake installer.install</em> from the command line, or install from the %s.',
				$this->Html->link(
					'backend',
					array(
						'admin' => true,
						'plugin' => 'installer',
						'controller' => 'plugins',
						'action' => 'install'
					)
				)
			); 
		}
		else {
			echo __d('cake_dev', 'Create the class <em>%s</em> below in file: %s', $class, (empty($plugin) ? APP_DIR . DS : CakePlugin::path($plugin)) . 'Controller' . DS . $class . '.php'); 
		}
	?>
</p>
<?php 
	if(!$pluginNotInstalled) { ?>
		<pre>&lt;?php
		class <?php echo $class . ' extends ' . $plugin; ?>AppController {

		} </pre><?php
	}
?>

<p class="notice">
	<strong><?php echo __d('cake_dev', 'Notice'); ?>: </strong>
	<?php echo __d('cake_dev', 'You can overload this error message from your theme directory by creating <em>%s</em>', 'APP/themed' . DS . 'your_theme' . DS . 'views' . DS . 'Errors' . DS . 'missing_controller.ctp'); ?>
</p>

<?php echo $this->element('exception_stack_trace'); ?>
