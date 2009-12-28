<?php
class FilterHelper extends Helper {
	var $helpers = array('Form');
	
	function form($model, $fields = array()) {
		$output = '<tr>';
		$output .= $this->Form->create($model, array('action' => 'index', 'id' => 'filters'));
		
		foreach($fields as $field) {
			if(empty($field)) {
				$output .= '<th>&nbsp;</th>';
			} else {
				$output .= '<th>' . $this->Form->input($field, array('label' => false)) . '</th>';
			}
		}
		
		$output .= '<th>'
			. $this->Form->button(__('Filter', true), array('type' => 'submit', 'name' => 'data[filter]'))
			. $this->Form->button(__('Reset', true), array('type' => 'submit', 'name' => 'data[reset]'))
			. '</th>';
	
		$output .= $this->Form->end();
		$output .= '</tr>';
		return $output;
	}
}
?>