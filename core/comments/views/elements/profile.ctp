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
	if(!isset($comments)){
		$comments = ClassRegistry::init('Comments.Comment')->getUsersComments($this->Session->read('Auth.User.id'));
	}
?>
<h3><?php echo __('Your Comments', true); ?></h3>
<?php
	if(count($comments) == 0){
		echo '<p>', __('You have not made any comments yet.', true), '</p>';
		return true;
	}

	foreach($comments as $comment){
		$_comments[] = $this->element('single_comment', array('plugin' => 'comments', 'comment' => $comment));
	}
	echo implode('', $_comments);
?>
