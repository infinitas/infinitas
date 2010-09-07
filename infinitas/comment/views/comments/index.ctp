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
<div class="comments">
	<?php
		foreach($comments as $comment){
			?>
				<div class="comment">
					<h3><?php echo $comment['Comment']['username']; ?></h3>
					<?php
						if(isset($comment['Comment']['website'])){
							echo '<h4>'.$comment['Comment']['website'].'</h4>';
						}
					?>
					<?php echo $this->Gravatar->image($comment['Comment']['email'], array('size' => '50')); ?>
					<p><?php echo $this->Text->truncate(strip_tags($comment['Comment']['comment']), 350); ?></p>
				</div>
			<?php
		}
	?>
</div>
