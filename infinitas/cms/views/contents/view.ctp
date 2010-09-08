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
	$eventData = $this->Event->trigger('cmsBeforeContentRender', array('_this' => $this, 'content' => $content));
	foreach((array)$eventData['cmsBeforeContentRender'] as $_plugin => $_data){
		echo '<div class="before '.$_plugin.'">'.$_data.'</div>';
	}
	
	?><style type="text/css"><?php echo $content['Layout']['css']; ?></style><?php

	// render the content template
	echo $this->Mustache->render(
		$content['Layout']['html'],
		$content
	);
	
	$eventData = $this->Event->trigger('cmsAfterContentRender', array('_this' => $this, 'content' => $content));
	foreach((array)$eventData['cmsAfterContentRender'] as $_plugin => $_data){
		echo '<div class="after '.$_plugin.'">'.$_data.'</div>';
	}