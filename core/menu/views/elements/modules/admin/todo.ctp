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
	$todo = $this->Event->trigger('requireTodoList');
	$todo = array_filter($todo['requireTodoList']);
	
	if(empty($todo)){
		return true;
	}

	array(
		'name' => '',
		'type' => '',
		'url' => ''
	);

	$out = '';

	foreach($todo as $name => $info) {
		$out .= '<h3>'.__(prettyName($name), true).'</h3>';
		$out .= '<ul>';
			foreach((array)$info as $item){
				$out .= '<li class="'.$item['type'].'">'.$this->Html->link(
					$item['name'],
					$item['url'],
					array(
						'title' => $item['name']
					)
				).'</li>';
			}
		$out .= '</ul>';
	}
?>
<div id="dock" class="todo">
	<div class="panel" style="display: none;">
		<div class="dashboard">
			<?php echo $out; ?>
		</div>
	</div>
	<a href="#" class="trigger"><?php echo __('Todo', true); ?></a>
</div>