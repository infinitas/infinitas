<?php
App::uses('CommentableBehavior', 'Comments.Model/Behavior');

class CommentableBehaviorTest extends CakeTestCase {

/**
 * fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.comments.infinitas_comment',
		'plugin.comments.infinitas_comment_attribute',
		'plugin.contents.global_tag',
		'plugin.contents.global_content',
		'plugin.contents.global_category',
		'plugin.contents.global_layout',
		'plugin.locks.lock',
		'plugin.management.ticket',
		'plugin.users.user',
		'plugin.users.group',
		'plugin.view_counter.view_counter_view',
		'plugin.blog.blog_post'
	);

/**
 * setup
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Commentable = new CommentableBehavior();
		$this->Post = ClassRegistry::init('Blog.BlogPost');
	}

/**
 * teardown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Commentable, $this->Post);
	}

/**
 * test setup
 */
	public function testSetup() {
		$this->Post->unbindModel(array(
			'hasMany' => array('BlogPostComment')
		));
		$result = array_keys($this->Post->hasMany);
		$this->assertFalse(in_array('BlogPostComment', $result));

		$this->Commentable->setup($this->Post);

		$result = array_keys($this->Post->hasMany);
		$this->assertTrue(in_array('BlogPostComment', $result));

		$expected = array(
			'className' => 'Blog.BlogPost',
			'foreignKey' => 'foreign_id',
			'conditions' => array(
				'BlogPostComment.class' => 'Blog.BlogPost'
			),
			'counterCache' => 'comment_count',
			'counterScope' => array(
				'BlogPostComment.active' => true
			),
			'fields' => null,
			'order' => null
		);
		$result = $this->Post->BlogPostComment->belongsTo['BlogPost'];
		$this->assertEquals($expected, $result);
	}

/**
 * test comment counter cache
 */
	public function testCommentCounterCache() {
		$expected = array(
			'blog-post-2' => 0,
			'blog-post-2-1' => 0,
			'blog-post-1' => 0
		);
		$result = $this->Post->find('list', array('fields' => array(
			'BlogPost.id',
			'BlogPost.comment_count'
		)));
		$this->assertEquals($expected, $result);

		$data = array(
			'user_id' => 'bob',
			'foreign_id' => 'blog-post-2',
			'username' => 'bob',
			'email' => 'bob@gmail.com',
			'comment' => 'This is the long comment that should not be spam bla bla bla etc something another thing.'
		);
		$result = $this->Post->createComment(array('BlogPostComment' => $data));
		$this->assertTrue((bool)$result);

		$expected['blog-post-2'] = 1;
		$result = $this->Post->find('list', array('fields' => array(
			'BlogPost.id',
			'BlogPost.comment_count'
		)));
		$this->assertEquals($expected, $result);

		$data['comment'] = 'some spam http://links.com and more http://things.com and so on';
		$result = $this->Post->createComment(array('BlogPostComment' => $data));
		$this->assertTrue((bool)$result);

		$result = $this->Post->find('list', array('fields' => array(
			'BlogPost.id',
			'BlogPost.comment_count'
		)));
		$this->assertEquals($expected, $result);

		$saved = (bool)$this->Post->BlogPostComment->save(array(
			'id' => $this->Post->BlogPostComment->id,
			'active' => 1
		));
		$this->assertTrue($saved);

		$expected['blog-post-2'] = 2;
		$result = $this->Post->find('list', array('fields' => array(
			'BlogPost.id',
			'BlogPost.comment_count'
		)));
		$this->assertEquals($expected, $result);
	}
}