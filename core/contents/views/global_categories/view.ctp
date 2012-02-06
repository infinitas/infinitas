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

	$eventData = $this->Event->trigger('contentsBeforeContentRender', array('_this' => $this, 'content' => $category));
	foreach((array)$eventData['contentsBeforeContentRender'] as $_plugin => $_data){
		echo '<div class="before '.$_plugin.'">'.$_data.'</div>';
	}
	if(isset($category['GlobalCategory']['created'])){
		$category['GlobalCategory']['created'] = $this->Time->niceShort($category['GlobalCategory']['created']);
	}
	if(isset($category['GlobalCategory']['modified'])){
		$category['GlobalCategory']['modified'] = $this->Time->niceShort($category['GlobalCategory']['modified']);
	}

	// need to overwrite the stuff in the viewVars for mustache
	$this->set('category', $category);

	if(!empty($category['Layout']['css'])){
		?><style type="text/css"><?php echo $category['Layout']['css']; ?></style><?php
	}

	// render the content template
	echo $category['Layout']['html'];


	$eventData = $this->Event->trigger('contentsAfterContentRender', array('_this' => $this, 'content' => $category));
	foreach((array)$eventData['contentsAfterContentRender'] as $_plugin => $_data){
		echo '<div class="after '.$_plugin.'">'.$_data.'</div>';
	}