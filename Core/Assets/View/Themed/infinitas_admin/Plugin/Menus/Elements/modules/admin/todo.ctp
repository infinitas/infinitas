<?php
$todo = $this->Event->trigger('requireTodoList');
$todo = array_filter($todo['requireTodoList']);

if(empty($todo)) {
	return;
}

$out = '';
return;

foreach($todo as $name => $info) {
	$out .= '<h3>'.__(prettyName($name)).'</h3>';
	$out .= '<ul>';
		foreach((array)$info as $item) {
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
	<a href="#" class="trigger"><?php echo __('Todo'); ?></a>
</div>