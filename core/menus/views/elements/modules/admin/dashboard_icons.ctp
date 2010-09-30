<?php
	/**
	 * Display a list of available plugin links
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.menu
	 * @subpackage Infinitas.menu.modules
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	$icons = $this->Menu->builDashboardLinks();

	if(isset($icons['core']) && !empty($icons['core'])){
		?>
			<div class="dashboard grid_16">
				<h1><?php echo __('Infinitas', true); ?></h1>
				<ul class="icons">
					<li><?php echo implode('</li><li>', $icons['core']); ?></li>
				</ul>
			</div>
			<div class="clear"></div>
		<?php
	}

	if(isset($icons['plugin']) && !empty($icons['plugin'])){
		?>			
			<div class="dashboard grid_16">
				<h1><?php echo __('Plugins', true); ?></h1>
				<ul class="icons">
					<li><?php echo implode('</li><li>', $icons['plugin']); ?></li>
				</ul>
			</div>
			<div class="clear"></div>
		<?php
	}