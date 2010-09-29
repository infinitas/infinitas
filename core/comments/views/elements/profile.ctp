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
	$comments = ClassRegistry::init('Comments.Comment')->getUsersComments($this->Session->read('Auth.User.id'));
?>
<h3><?php echo __('Your Comments', true); ?></h3>
<?php
	if(count($comments) == 0){
		echo '<p>', __('You have not made any comments yet.', true), '</p>';
	}
	else{
		foreach($comments as $comment){
			$_comments[] =
				'<div class="comment">'.
					$this->Gravatar->image($comment['email'], array('size' => '50')).
					'<p>'.$this->Text->truncate(strip_tags($comment['comment']), 350).'</p>'.
				'</div>';
		}

		echo implode('', $_comments);
	}
?>
