<?php
/**
 * Display a single comment
 * 
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * 
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Comments.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

if (!isset($comment)) {
	return false;
}
$comment = isset($comment['InfinitasComment']) ? $comment['InfinitasComment'] : $comment;

$comment['comment'] = str_replace('\\n', '', strip_tags($comment['comment']));
if ($this->plugin != 'Comments') {
	$comment['comment'] = $this->Text->truncate($comment['comment'], 350);
}

$link = null;
if (isset($comment['website']) && isset($comment['username'])) {
	$link = $this->Html->link(
		$comment['username'],
		$comment['website'], array('rel' => 'nofollow')
	);
} else if (isset($comment['username'])) {
	$link = $comment['username'];
}

echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('h4', implode('', array(
		$link,
		$this->Html->tag('small', $this->Time->timeAgoInWords($comment['created']))
	))),
	$this->Html->tag('p', implode('', array(
		$this->Gravatar->image($comment['email'], array('size' => '75')),
		$comment['comment']
	)))
)), array('class' => 'comment'));