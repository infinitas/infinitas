<?php
App::uses('InfinitasEventTestCase', 'Events.Test/Lib');
class CommentsEventsTest extends InfinitasEventTestCase {
	public $fixtures = array(
		'plugin.configs.config',
		'plugin.themes.theme',
		'plugin.routes.route',
		'plugin.view_counter.view_counter_view',

		'plugin.comments.infinitas_comment',
		'plugin.comments.infinitas_comment_attribute'
	);
}