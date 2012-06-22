<?php
	App::uses('InfinitasContainableBehavior', 'Libs.Model/Behavior');

	App::uses('Model', 'Model');
	App::uses('AppModel', 'Model');

	$File = new File(CAKE . DS . 'Test' . DS .'Case' . DS . 'Model' . DS . 'models.php');
	$search = array('<?php', 'class AppModel', 'class Article');
	$replace = array('', 'class CakeTestAppModelIgnore', 'class CakeTestArticleModelIgnore');
	eval(str_replace($search, $replace, $File->read()));

	/**
	 * InfinitasContainableBehavior Test Case
	 *
	 */
	class InfinitasContainableBehaviorTestCase extends CakeTestCase {
		public $fixtures = array(
			'core.article', 'core.article_featured', 'core.article_featureds_tags',
			'core.articles_tag', 'core.attachment', 'core.category',
			'core.comment', 'core.featured', 'core.tag', 'core.user',
			'core.join_a', 'core.join_b', 'core.join_c', 'core.join_a_c', 'core.join_a_b'
		);

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();

			$this->User = ClassRegistry::init('User');
			$this->Article = ClassRegistry::init('Article');
			$this->Tag = ClassRegistry::init('Tag');

			$this->User->bindModel(
				array(
					'hasMany' => array(
						'Article',
						'ArticleFeatured',
						'Comment'
					)
				),
				false
			);
			$this->User->ArticleFeatured->unbindModel(array('belongsTo' => array('Category')), false);
			$this->User->ArticleFeatured->hasMany['Comment']['foreignKey'] = 'article_id';

			$this->Tag->bindModel(
				array(
					'hasAndBelongsToMany' => array('Article')
				),
				false
			);

			$this->User->Behaviors->attach('Libs.InfinitasContainable');
			$this->Article->Behaviors->attach('Libs.InfinitasContainable');
			$this->Tag->Behaviors->attach('Libs.InfinitasContainable');
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			unset($this->Article);
			unset($this->User);
			unset($this->Tag);

			parent::tearDown();
		}

		/**
		 * testBeforeFindWithNonExistingBinding method
		 *
		 * @expectedException PHPUnit_Framework_Error
		 * @return void
		 */
		public function atestBeforeFindWithNonExistingBinding() {
			$r = $this->Article->find('all', array('contain' => array('Comment' => 'NonExistingBinding')));
		}

		/**
		 * testFindEmbeddedNoBindings method
		 *
		 * @return void
		 */
		public function testFindEmbeddedNoBindings() {
			$result = $this->Article->find('all', array('contain' => false));
			$expected = array(
				array('Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				)),
				array('Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				)),
				array('Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				))
			);
			$this->assertEquals($expected, $result);
		}

		public function testBelongsToFind() {
			$result = $this->Article->find('all', array('contain' => array('User')));

			$this->assertEqual(count($result), 3);
			$this->assertEqual(count($result[0]), 2);
			$this->assertTrue(isset($result[0]['Article']));
			$this->assertTrue(isset($result[0]['User']));

			$result = $this->Article->find('first', array('contain' => array('User')));

			$expected = array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'));
			$this->assertEqual($result, $expected);

			$result = $this->Article->find('first', array(
				'fields' => array('Article.id'),
				'contain' => array('User' => array('fields' => array('User.user')))));

			$expected = array(
				'Article' => array('id' => 1),
				'User' => array('user' => 'mariano')
			);
			$this->assertEqual($result, $expected);

			$result = $this->Article->find('count', array('contain' => array('User'), 'conditions' => array('User.id' => 1)));
			$this->assertEqual($result, 2);
		}

		public function testHasManyFind() {
			$expected = array(array(
				'User' => array(
					'id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'),
					'Article' => array(array(
						'id' => '1', 'user_id' => '1', 'title' => 'First Article', 'body' => 'First Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'), array(
						'id' => '3', 'user_id' => '1', 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'))), array(
				'User' => array('id' => '2', 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'),
							'Article' => array()), array(
				'User' => array(
					'id' => '3', 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'),
					'Article' => array(array(
						'id' => '2', 'user_id' => '3', 'title' => 'Second Article', 'body' => 'Second Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'))),array(
				'User' => array('id' => '4', 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'),
					'Article' => array()));
			$result = $this->User->find(
				'all',
				array(
					'contain' => array(
						'Article'
					)
				)
			);

			$this->assertEqual($result, $expected);
			$expected = array(array(
				'User' => array(
					'id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'),
					'Article' => array()), array(
				'User' => array('id' => '2', 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'),
							'Article' => array()), array(
				'User' => array(
					'id' => '3', 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'),
					'Article' => array(array(
						'id' => '2', 'user_id' => '3', 'title' => 'Second Article', 'body' => 'Second Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'))),array(
				'User' => array('id' => '4', 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'),
					'Article' => array()));
			$result = $this->User->find(
				'all',
				array(
					'contain' => array(
						'Article' => array(
							'conditions' => array(
								'Article.id' => 2
							)
						)
					)
				)
			);

			$this->assertEqual($result, $expected);

			$expected = array(array(
				'User' => array(
					'id' => '3', 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'),
					'Article' => array(array(
						'id' => '2', 'user_id' => '3', 'title' => 'Second Article', 'body' => 'Second Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'))));
			$result = $this->User->find(
				'all',
				array(
					'conditions' => array(
						'User.id' => 3
					),
					'contain' => array(
						'Article' => array(
							'conditions' => array(
								'Article.id' => 2
							)
						)
					)
				)
			);

			$this->assertEqual($result, $expected);

			$expected = array(array(
				'User' => array(
					'id' => '3', 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'),
					'Article' => array()));
			$result = $this->User->find(
				'all',
				array(
					'conditions' => array(
						'User.id' => 3
					),
					'contain' => array(
						'Article' => array(
							'conditions' => array(
								'Article.id' => 25
							)
						)
					)
				)
			);

			$this->assertEqual($result, $expected);
		}

		public function testHasOneFind() {
			$this->Article->unbindModel(array('hasMany' => array('Comment')), true);
			unset($this->Article->Comment);
			$this->Article->bindModel(array('hasOne' => array('Comment')));

			$result = $this->Article->find('all', array(
				'fields' => array('title', 'body'),
				'contain' => array(
					'Comment' => array('fields' => array('Comment.comment')),
					'User' => array('fields' => array('User.user')))));
			$this->assertTrue(isset($result[0]['Article']['title']), 'title missing %s');
			$this->assertTrue(isset($result[0]['Article']['body']), 'body missing %s');
			$this->assertTrue(isset($result[0]['Comment']['comment']), 'comment missing %s');
			$this->assertTrue(isset($result[0]['User']['user']), 'body missing %s');
			$this->assertFalse(isset($result[0]['Comment']['published']), 'published found %s');
			$this->assertFalse(isset($result[0]['User']['password']), 'password found %s');
		}

		/**
		 * testFindEmbeddedSecondLevel method
		 *
		 * @return void
		 */
		public function testFindEmbeddedSecondLevel() {
			$result = $this->Article->find(
				'all',
				array(
					'contain' => array(
						'Comment' => array(
							'fields' => array(
								'User.*',
								'Comment.*'
							),
							'User'
						)
					)
				)
			);
			$expected = array(
				array(
					'Article' => array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
					),
					'Comment' => array(
						array(
							'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
							'User' => array(
								'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
								'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
							)
						),
						array(
							'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
							'User' => array(
								'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
								'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
							)
						),
						array(
							'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
							'User' => array(
								'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
								'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
							)
						),
						array(
							'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
							'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
							'User' => array(
								'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
								'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
							)
						)
					)
				),
				array(
					'Article' => array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
					),
					'Comment' => array(
						array(
							'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
							'User' => array(
								'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
								'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
							)
						),
						array(
							'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
							'User' => array(
								'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
								'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
							)
						)
					)
				),
				array(
					'Article' => array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
					),
					'Comment' => array()
				)
			);
			$this->assertEquals($expected, $result);

			return;

			$result = $this->Article->find('all', array('contain' => array('User' => array('ArticleFeatured'))));
			$expected = array(
				array(
					'Article' => array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
					),
					'User' => array(
						'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
						'ArticleFeatured' => array(
							array(
								'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
							),
							array(
								'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
							)
						)
					)
				),
				array(
					'Article' => array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
					),
					'User' => array(
						'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
						'ArticleFeatured' => array(
							array(
							'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
							)
						)
					)
				),
				array(
					'Article' => array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
					),
					'User' => array(
						'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
						'ArticleFeatured' => array(
							array(
								'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
							),
							array(
								'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
							)
						)
					)
				)
			);
			pr($result[0]['User']);
			pr($expected[0]['User']);
			$this->assertEquals($expected, $result);

			$result = $this->Article->find('all', array('contain' => array('User' => array('ArticleFeatured', 'Comment'))));
			$expected = array(
				array(
					'Article' => array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
					),
					'User' => array(
						'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
						'ArticleFeatured' => array(
							array(
								'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
							),
							array(
								'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
							),
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
							)
						)
					)
				),
				array(
					'Article' => array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
					),
					'User' => array(
						'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
						'ArticleFeatured' => array(
							array(
							'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
							)
						),
						'Comment' => array()
					)
				),
				array(
					'Article' => array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
					),
					'User' => array(
						'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
						'ArticleFeatured' => array(
							array(
								'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
							),
							array(
								'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
							),
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
							)
						)
					)
				)
			);
			$this->assertEquals($expected, $result);

			$result = $this->Article->find('all', array('contain' => array('User' => 'ArticleFeatured', 'Tag', 'Comment' => 'Attachment')));
			$expected = array(
				array(
					'Article' => array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
					),
					'User' => array(
						'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
						'ArticleFeatured' => array(
							array(
								'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
							),
							array(
								'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
							)
						)
					),
					'Comment' => array(
						array(
							'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
							'Attachment' => array()
						),
						array(
							'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
							'Attachment' => array()
						),
						array(
							'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
							'Attachment' => array()
						),
						array(
							'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
							'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
							'Attachment' => array()
						)
					),
					'Tag' => array(
						array('id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'),
						array('id' => 2, 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31')
					)
				),
				array(
					'Article' => array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
					),
					'User' => array(
						'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
						'ArticleFeatured' => array(
							array(
							'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
							)
						)
					),
					'Comment' => array(
						array(
							'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
							'Attachment' => array(
								'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
								'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
							)
						),
						array(
							'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
							'Attachment' => array()
						)
					),
					'Tag' => array(
						array('id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'),
						array('id' => 3, 'tag' => 'tag3', 'created' => '2007-03-18 12:26:23', 'updated' => '2007-03-18 12:28:31')
					)
				),
				array(
					'Article' => array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
					),
					'User' => array(
						'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
						'ArticleFeatured' => array(
							array(
								'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
							),
							array(
								'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
								'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
							)
						)
					),
					'Comment' => array(),
					'Tag' => array()
				)
			);
			$this->assertEquals($expected, $result);
		}
	}
