<?php
/**
	* Management Comments admin index view file.
	*
	* this is the admin index file that displays a list of comments in the
	* admin section of the blog plugin.
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	*
	* @filesource
	* @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link          http://infinitas-cms.org
	* @package       blog
	* @subpackage    blog.views.comments.admin_index
	* @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	*/

echo $this->Form->create('InfinitasComment', array('action' => 'mass'));
echo $this->Infinitas->adminIndexHead($filterOptions, array(
	'edit',
	'reply',
	'toggle',
	'spam',
	'ban',
	'delete'
));

$this->Infinitas->adminTableHeader(array(
	$this->Form->checkbox('all') => array(
		'class' => 'first'
	),
	$this->Paginator->sort(__d('comments', 'Where'), 'class'),
	$this->Paginator->sort('email') => array(
		'style' => '50px;'
	),
	$this->Paginator->sort('ip_address'),
	$this->Paginator->sort('created') => array(
		'class' => 'date'
	),
	$this->Paginator->sort('points') => array(
		'width' => '50px'
	),
	__d('comments', 'Status') => array(
		'class' => 'actions'
	)
));
echo $this->Form->checkbox('all');
foreach ($comments as $comment) {
	$class = null;
	$tags = array();
	if(!$comment['InfinitasComment']['mx_record']) {
		$class = 'text-warning';
		$tags[] = $this->Html->tag('div', __d('comments', 'MX Record'), array(
			'class' => 'label label-warning'
		));
	}
	if (!$comment['InfinitasComment']['active']) {
		$class = 'text-warning';
		$tags[] = $this->Html->tag('div', __d('comments', 'Disabled'), array(
			'class' => 'label label-info'
		));
	}

	if ($comment['InfinitasComment']['status'] == 'spam') {
		$class = 'text-error';
		$tags[] = $this->Html->tag('div', __d('comments', 'Spam'), array(
			'class' => 'label label-important'
		));
	}
	$points = 'badge-success';
	if($comment['InfinitasComment']['points'] < 3) {
		$points = 'badge badge-warning';
		if($comment['InfinitasComment']['points'] < 0) {
			$points = 'badge badge-important';
		}
	}
	if (!$comment['InfinitasComment']['subscribed']) {
		$tags[] = $this->Design->label(__d('comments', 'Unsubscribed'));
	}
	$tags[] = $this->Html->tag('div', $comment['InfinitasComment']['points'], array(
		'class' => array(
			'badge',
			$points
		)
	));

	$url = current($this->Event->trigger('Comments.slugUrl', array(
		'data' => array('id' => $comment['InfinitasComment']['id'])
	)));
	$url['Comments'] = array_merge($url['Comments'], array(
		'admin' => false
	));
	$title = $this->Infinitas->massActionCheckBox($comment) . $this->Html->link($comment['InfinitasComment']['post'], array(
		'action' => 'edit',
		$comment['InfinitasComment']['id']
	));

	$title .= $this->Infinitas->date($comment['InfinitasComment']['created']);

	echo $this->Html->tag('div', implode('', array(
		$this->Html->link($this->Gravatar->image($comment['InfinitasComment']['email']), 'mailto://' . $comment['InfinitasComment']['email'], array(
			'escape' => false,
			'class' => 'pull-left'
		)),
		$this->Html->tag('div', implode('', array(
			$this->Html->tag('h4', $title),
			$this->Html->tag('p', str_replace('\n', ' ', strip_tags($comment['InfinitasComment']['comment'])), array(
				'class' => $class
			)),
			$this->Html->tag('div', implode('', array(
				$this->Html->tag('div', implode('', array(
					$this->Html->link(__d('comments', 'View'), $url['Comments'], array(
						'escape' => false,
						'target' => 'blank',
						'class' => 'btn btn-small'
					)),
					$this->Html->tag('div', $comment['InfinitasComment']['ip_address'] ?: '-', array('class' => 'btn btn-small')),
					$this->Text->autoLinkEmails($comment['InfinitasComment']['email'], array('class' => 'btn btn-small')),
				)), array('class' => 'btn-group pull-left')),
				$this->Html->tag('div', implode('', $tags), array(
					'class' => 'labels'
				))
			)), array('class' => 'row', 'style' => 'padding-left: 20px;'))
		)), array('class' => 'media-body')),
		$this->Html->tag('hr')
	)), array('class' => 'media'));
}

echo $this->Form->end();
echo $this->element('pagination/admin/navigation');