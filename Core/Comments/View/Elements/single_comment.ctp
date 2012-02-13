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
	if(!isset($comment)){
		return false;
	}
	$comment = isset($comment['InfinitasComment']) ? $comment['InfinitasComment'] : $comment;

	$comment['comment'] = str_replace('\\n', '', strip_tags($comment['comment']));
	if($this->plugin != 'Comments'){
		$comment['comment'] = $this->Text->truncate($comment['comment'], 350);
	}
?>
<div class="comment">
	<h4>
		<?php
			if(isset($comment['website']) && isset($comment['username'])){
				echo $this->Html->link(
					$comment['username'],
					$comment['website'], array('rel' => 'nofollow')
				);
			}
			else if(isset($comment['username'])){
				echo $comment['username'];
			}
		?>
		<small><?php echo $this->Time->timeAgoInWords($comment['created']); ?></small>
	</h4>
	<?php echo $this->Gravatar->image($comment['email'], array('size' => '50')); ?>
	<p><?php echo $comment['comment']; ?></p>
</div>