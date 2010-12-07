<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
?>
<style type="text/css">
	.listing.no-gap{
		margin-bottom: 0;
	}

	h1.no-gap{
		margin-top: 10px;
	}
</style>
<?php
	ob_start();
		phpinfo();
		$pinfo = ob_get_contents();
	ob_end_clean();

	$phpInfo = str_replace('module_Zend Optimizer', 'module_Zend_Optimizer', preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo));
	$phpInfo = str_replace(array('<table ', '<h2>', '</h2>', '<h1'), array('<table class="listing no-gap" ', '<h1 class="no-gap">', '</h1>', '<h1 class="no-gap" '), $phpInfo);
	$phpInfo = str_replace(array('<br />', '<hr />'), '', $phpInfo);

	echo $phpInfo;