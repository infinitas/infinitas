<?php
	App::uses('InfinitasContainableBehavior', 'Libs.Model/Behavior');
	
	App::uses('Model', 'Model');
	App::uses('AppModel', 'Model');
	
	require_once CAKE . DS . 'Test' . DS .'Case' . DS . 'Model' . DS . 'models.php';

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
		}
	}
