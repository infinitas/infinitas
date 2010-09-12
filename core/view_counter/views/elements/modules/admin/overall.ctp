<?php
	/**
	 * Generats a graph for overall view stas.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.view_counter
	 * @subpackage Infinitas.view_counter.modules
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	$viewStats = ClassRegistry::init('ViewCounter.ViewCount')->getGlobalTotalCount();
	if(empty($viewStats)){
		return;
	}
		?>
			<div class="dashboard small">
				<h1><?php echo sprintf(__('Overall Usage', true)); ?></h1>
				<?php
					echo $this->Chart->display(
						'pie3d',
						array(
							'data' => array_values($viewStats),
							'labels' => array_keys($viewStats),
							'size' => '280,100'
						)
					);
				?>
				<p><?php echo sprintf(__('%s views in total', true), array_sum($viewStats)); ?></p>
			</div>
		<?php
?>